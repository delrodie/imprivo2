<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/employe')]
class ApiEmployeController extends AbstractController
{
    #[Route('/', name: 'api_employe_index')]
    public function index(EmployeRepository $employeRepository): Response
    {
        $employes = $employeRepository->findAll();

        $data = [];
        foreach ($employes as $index => $employe) {
            $data[] = [
                'id' => $index + 1,
                'code' => $employe->getCode(),
                'identite' => strtoupper($employe->getNom()).' '.strtoupper($employe->getPrenom()),
                'fonction' => $employe->getFonction(),
                'telephone' => $employe->getTelephone(),
                'user' => $employe->getUser()->getEmail(),
                'actions' => [
                    'detail' => $this->generateUrl('app_employe_show',['id' => $employe->getId()]),
                    'edit' => $this->generateUrl('app_employe_edit',['id' => $employe->getId()]),
                    'delete' => $this->generateUrl('app_employe_delete',['id' => $employe->getId()]),
                ]
            ];
        }

        return new JsonResponse(['data' => $data]);
    }
}
