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

        $canCreate = $this->isGranted('client.create');
        $canRead = $this->isGranted('client.read');
        $canUpdate = $this->isGranted('client.update');
        $canDelete = $this->isGranted('client.delete');

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
                    'detail' => $canRead ? $this->generateUrl('app_client_show',['id' => $client->getId()]): null,
                    'edit' => $canUpdate ? $this->generateUrl('app_client_edit',['id' => $client->getId()]): null,
                    'delete' => $canDelete ? $this->generateUrl('app_client_delete',['id' => $client->getId()]) : null ,
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
