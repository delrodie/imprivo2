<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\DevisLigne;
use App\Enum\DevisStatut;
use App\Enum\SequenceDocType;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use App\Services\SequenceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/devis')]
class DevisController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DevisRepository        $devisRepository,
        private readonly SequenceService $sequenceService,
        private readonly Security $security
    )
    {
    }

    #[Route('/', name: 'app_devis_index')]
    #[isGranted("devis.read")]
    public function index(Request $request): Response
    {
        $devis = new Devis();
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        return $this->render('devis/index.html.twig',[
            'devis' => $devis,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET','POST'])]
//    #[isGranted('devis.create')]
    public function new(Request $request): JsonResponse|Response|null
    {
        $devis = new Devis();
        return $this->handleForm($request, $devis, true);
    }

    #[Route('/{id}', name: 'app_devis_show', methods: ['GET'])]
    #[isGranted('devis.read')]
    public function show(Devis $devis)
    {

    }

    #[Route('/{id}/edit', name: 'app_devis_edit', methods: ['GET','POST'])]
    #[isGranted('devis.edit')]
    public function edit(Request $request, Devis $devis)
    {
        return $this->handleForm($request, $devis);
    }

    #[Route('/{id}', name: 'app_devis_delete', methods: ['DELETE'])]
    #[isGranted('devis.delete')]
    public function delete(Devis $devis): JsonResponse|Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $devis->getStatut() !== DevisStatut::BROUILLON){
            return new JsonResponse([
                'success' => false,
                'message' => 'Impossible de supprimer un devis qui n’est pas en brouillon.'
            ], Response::HTTP_FORBIDDEN);
        }

        if ($devis->getStatut() === DevisStatut::TRANSFORME){
            return new JsonResponse([
                'success' => false,
                'message' => 'Impossible de supprimer un devis déjà transformé en facture.'
            ], Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->remove($devis);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);

    }

    private function handleForm(Request $request, Devis $devis, bool $isNew = false)
    {
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        // ✅ Cas GET → affichage initial du formulaire
        if ($request->isMethod('GET')) {
            return $this->render('devis/_form.html.twig', [
                'form' => $form->createView(),
                'devis' => $devis
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion du statut via le bouton submit
            $action = $request->request->get('btnAction');
            if ($action === 'envoyer'){
                $devis->setStatut(DevisStatut::ENVOYE);
                $devis->setSendedAt(new \DateTimeImmutable());
                $devis->setSendedBy($this->security->getUser()->getUserIdentifier());
            }else{
                $devis->setStatut(DevisStatut::BROUILLON);
            }

            if ($isNew) {
                $devis->setNumero($this->sequenceService->generateNumero(SequenceDocType::DEV)) ;

                // Synchronisation des lignes
                foreach ($devis->getLignes() as $ligne){
                    $ligne->setDevis($devis);
                }

                $this->entityManager->persist($devis);
            }

            $this->entityManager->flush();

            $devis = new Devis(); // nouveau formulaire vierge
            $form = $this->createForm(DevisType::class, $devis);

            return new JsonResponse([
                'success' => true,
                'form' => $this->renderView('devis/_form.html.twig', [
                    'form' => $form->createView(),
                    'devis' => $devis
                ])
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'form' => $this->renderView('devis/_form.html.twig', [
                'form' => $form->createView(),
                'devis' => $devis
            ])
        ]);
    }
}
