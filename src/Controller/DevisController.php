<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Devis;
use App\Enum\DevisStatut;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/devis')]
class DevisController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DevisRepository $devisRepository
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
    #[isGranted('devis.create')]
    public function new(Request $request)
    {
        $devis = new Devis();
        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);
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
    public function delete(Devis $devis)
    {
        if (!$this->isGranted('ROLE_ADMIN')){
            if ($devis->getStatut() !== DevisStatut::BROUILLON){
                // Retourner message json d'echec
            }
        }

        if ($devis->getStatut() === DevisStatut::TRANSFORME){
            // Retetourner message d'echec parce que le devis a été transformé en facture
        }

        $this->entityManager->remove($devis);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);

    }

    private function handleForm(Request $request, Devis $devis, bool $isNew = false)
    {
    }
}
