<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\DevisLigneRepository;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/devis')]
class ApiDevisController extends AbstractController
{
    #[Route('/', name: 'api_devis_index')]
    public function index(DevisRepository $devisRepository, DevisLigneRepository $ligneRepository): Response
    {
        $devis = $devisRepository->findAll();
        $data=[];

        $canCreate = $this->isGranted('devis.create');
        $canRead = $this->isGranted('devis.read');
        $canUpdate = $this->isGranted('devis.update');
        $canDelete = $this->isGranted('devis.delete');

        foreach ($devis as $index => $devi) {
            $data[]=[
                'id' => $index +1,
                'numero' => $devi->getNumero(),
                'client' => $devi->getClient()->getNom(),
                'date' => $devi->getDate(),
                'montant' => $devi->getMontant(),
                'statut' => $devi->getStatut(),
                'actions' => [
                    'detail' => $canRead ? $this->generateUrl('app_devis_show',['id' => $devi->getId()]): null,
                    'edit' => $canUpdate ? $this->generateUrl('app_devis_edit',['id' => $devi->getId()]): null,
                    'delete' => $canDelete ? $this->generateUrl('app_devis_delete',['id' => $devi->getId()]) : null ,
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
