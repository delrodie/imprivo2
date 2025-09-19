<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\UniteMesureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/unite-mesure')]
class ApiUniteMesureController extends AbstractController
{
    #[Route('/', name:'api_unite_mesure_index')]
    public function index(UniteMesureRepository $mesureRepository): Response
    {
        $uniteMesures = $mesureRepository->findAll();
        $data = [];
        foreach ($uniteMesures as $index => $unite) {
            $data[] = [
                'id' => $index + 1,
                'code' => $unite->getCode(),
                'libelle' => $unite->getLibelle(),
                'symbole' => $unite->getSymbole(),
                'statut' => $unite->isActif() ? 'ACTIF' : 'DESCATIVE',
                'actions' => [
                    'edit' => $this->generateUrl('app_unitemesure_edit',['id' => $unite->getId()]),
                    'delete' => $this->generateUrl('app_unitemesure_delete',['id' => $unite->getId()]),
                ]
            ];
        }

        return new JsonResponse(['data' => $data]);
    }
}
