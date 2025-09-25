<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/facture')]
class ApiFactureController extends AbstractController
{
    public function __construct(private readonly FactureRepository $factureRepository)
    {
    }

    #[Route('/', name: 'api_facture_index')]
    public function index(FactureRepository $factureRepository): Response
    {
        $factures = $factureRepository->findAll();
        $data=[];

        $canCreate = $this->isGranted('facture.create');
        $canRead = $this->isGranted('facture.read');
        $canUpdate = $this->isGranted('facture.update');
        $canDelete = $this->isGranted('facture.delete');

        foreach ($factures as $index => $facture) {
            $data[]=[
                'id' => $index +1,
                'numero' => $facture->getNumero(),
                'client' => $facture->getClient()?->getNom(),
                'date' => $facture->getDate()?->format('Y-m-d'),
                'montant' => $facture->getTotalTTC(),
                'statut' => $facture->getStatut(),
                'actions' => [
                    'detail' => $canRead ? $this->generateUrl('app_facture_show',['uuid' => $facture->getUuid()]): null,
//                    'edit' => $canUpdate ? $this->generateUrl('app_devis_edit',['id' => $facture->getId()]): null,
                    'delete' => $canDelete ? $this->generateUrl('app_devis_delete',['id' => $facture->getId()]) : null ,
                ]
            ];
        }
        return new JsonResponse([
            'data' => $data,
            'permissions' => [
                'create' => $canCreate,
                'read' => $canRead,
                'update' => $canUpdate,
                'delete' => $canDelete
            ]
        ]);
    }

    #[Route('/{statut}', name: 'api_facture_statut', methods: ['GET'])]
    public function statut($statut)
    {
        $factures = $this->factureRepository->findBy(['statut' => $statut]);
        $data=[];

        $canCreate = $this->isGranted('facture.create');
        $canRead = $this->isGranted('facture.read');
        $canUpdate = $this->isGranted('facture.update');
        $canDelete = $this->isGranted('facture.delete');
        $facAnnul = $statut && $statut === 'ANNULEE';
        $facValid = $statut && $statut === 'VALIDEE';


        foreach ($factures as $index => $facture) {
            $data[]=[
                'id' => $index +1,
                'numero' => $facture->getNumero(),
                'client' => $facture->getClient()?->getNom(),
                'date' => $facture->getDate()?->format('Y-m-d'),
                'montant' => $facture->getTotalTTC(),
                'statut' => $facture->getStatut(),
                'actions' => [
                    'detail' => $facValid ? $this->generateUrl('app_facture_show',['uuid' => $facture->getUuid()]): null,
                    'edit' => $facAnnul ? $this->generateUrl('app_devis_edit',['id' => $facture->getId()]): null,
                    'delete' => $canDelete ? $this->generateUrl('app_devis_delete',['id' => $facture->getId()]) : null ,
                ]
            ];
        }
        return new JsonResponse([
            'data' => $data,
            'permissions' => [
                'create' => $canCreate,
                'read' => $canRead,
                'update' => $canUpdate,
                'delete' => $canDelete
            ]
        ]);
    }
}
