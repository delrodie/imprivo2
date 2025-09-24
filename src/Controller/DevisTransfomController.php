<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\DevisStatut;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/devis-transfom')]
class DevisTransfomController extends AbstractController
{
    public function __construct(
        private readonly DevisRepository $devisRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name:'app_devistransfom_update', methods: ['GET','POST'])]
    public function update(Request $request): Response
    {
        $devisId = $request->request->get('devis_id');
        $btnSubmit = $request->request->get('devis_btn');
        $devis = $this->devisRepository->findOneBy(['id' => $devisId]); dump($request);

        if ($this->isCsrfTokenValid(
            'update'.$devis->getId(),
            $request->request->get('_token')
        )){
            // Bouton Envoyer
            if ($btnSubmit === 'envoyer') {
                $devis->setStatut(DevisStatut::ENVOYE);
            } elseif ($btnSubmit === 'accepter') {
                $devis->setStatut(DevisStatut::ACCEPTE);
            } elseif ($btnSubmit === 'rejeter') {
                $devis->setStatut(DevisStatut::REFUSE);
            } elseif ($btnSubmit === 'transformer') {
                $devis->setStatut(DevisStatut::TRANSFORME);
            } elseif ($btnSubmit === 'brouillon') {
                $devis->setStatut(DevisStatut::BROUILLON);
            }

            $this->entityManager->flush();
        }

        // si tu veux renvoyer du JSON (plus propre avec Stimulus)
        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true, 'newStatut' => $devis->getStatut()->value]);
        }

        return $this->redirectToRoute('app_devis_index');
    }
}
