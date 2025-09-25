<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\FactureStatut;
use App\Repository\FactureRepository;
use App\Services\FactureManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/facture')]
class FactureController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FactureRepository      $factureRepository,
        private readonly FactureManager $factureManager,
    )
    {
    }

    #[Route('/', name: 'app_facture_index')]
    public function index(): Response
    {
        return $this->render('facture/index.html.twig');
    }

    #[Route('/{uuid}', name: 'app_facture_show', methods: ['GET'])]
    public function show($uuid): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $this->factureRepository->findOneBy(['uuid' => $uuid]),
        ]);
    }

    #[Route('/update', name: 'app_facture_update', methods: ['POST'])]
    public function update(Request $request)
    {
        $factureUuid = $request->request->get('facture_id');
        $btnSubmit = $request->request->get('facture_btn');
        $facture = $this->factureRepository->findOneBy(['uuid' => $factureUuid]);

        if($this->isCsrfTokenValid(
            'update'.$facture->getId(),
            $request->request->get('_token')
        )){
            match ($btnSubmit){
                'valider' => $this->factureManager->updateStatut($facture, FactureStatut::VALIDEE),
                'annuler' => $this->factureManager->updateStatut($facture, FactureStatut::ANNULEE),
                'payer' => $this->factureManager->updateStatut($facture, FactureStatut::PAYEE),
                default => null
            };

            $this->entityManager->flush();
        }

        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true, 'newStatut' => $facture->getStatut()->value]);
        }

        return $this->redirectToRoute('app_facture_index');
    }

    #[Route('/{statut}/liste', name: 'app_facture_statut', methods: ['GET'])]
    public function statut($statut): Response
    {
        return $this->render('facture/list_statut.html.twig',[
            'factures' => $this->factureRepository->findBy(['statut' => $statut]),
            'statut' => $statut
        ]);
    }
}
