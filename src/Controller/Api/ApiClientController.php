<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/client')]
class ApiClientController extends AbstractController
{
    #[Route('/', name: 'api_client_index')]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        $data=[];
        foreach ($clients as $index => $client) {
            $data[]=[
                'id' => $index +1,
                'code' => $client->getCode(),
                'nom' => $client->getNom(),
                'telephone' => $client->getTelephone(),
                'email' => $client->getEmail(),
                'ville' => $client->getVille(),
                'type' => $client->getType(),
                'actif' => $client->isActif() ? 'ACTIF' : 'DESACTIVE',
                'actions' => [
                    'detail' => $this->generateUrl('app_client_show',['id' => $client->getId()]),
                    'edit' => $this->generateUrl('app_client_edit',['id' => $client->getId()]),
                    'delete' => $this->generateUrl('app_client_delete',['id' => $client->getId()]),
                ]
            ];
        }
        return new JsonResponse(['data' => $data]);
    }
}
