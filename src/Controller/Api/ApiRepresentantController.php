<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\RepresentantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/representant')]
class ApiRepresentantController extends AbstractController
{
    #[Route('/', name: 'api_representant_index')]
    public function index(RepresentantRepository $reprentantRepository): Response
    {
        $representants = $reprentantRepository->findAll();
        $data=[];

        $canCreate = $this->isGranted('representant.create');
        $canRead = $this->isGranted('representant.read');
        $canUpdate = $this->isGranted('representant.update');
        $canDelete = $this->isGranted('representant.delete');

        foreach ($representants as $index => $representant) {
            $data[]=[
                'id' => $index +1,
                'client' => $representant->getClient()->getNom(),
                'nom' => $representant->getNom(). ' ' .$representant->getPrenom(),
                'fonction' => $representant->getFonction(),
                'telephone' => $representant->getTelephone2() ? $representant->getTelephone1().' - '.$representant->getTelephone2() : $representant->getTelephone1(),
                'email' => $representant->getEmail(),
                'actions' => [
                    'detail' => $canRead ? $this->generateUrl('app_representant_show',['id' => $representant->getId()]): null,
                    'edit' => $canUpdate ? $this->generateUrl('app_representant_edit',['id' => $representant->getId()]): null,
                    'delete' => $canDelete ? $this->generateUrl('app_representant_delete',['id' => $representant->getId()]) : null ,
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
