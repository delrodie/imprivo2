<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\PermissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/permission')]
class ApiPermissionController extends AbstractController
{
    #[Route('/', name:'api_permission_index')]
    public function index(PermissionRepository $permissionRepository): Response
    {
        $permissions = $permissionRepository->findAll();

        $data = [];
        foreach ($permissions as $index => $permission) {
            $data[] = [
                'id' => $index + 1,
                'code' => $permission->getCode(),
                'label' => $permission->getLabel(),
                'actions' => [
//                    'detail' => $this->generateUrl('app_employe_show',['id' => $permission->getId()]),
                    'edit' => $this->generateUrl('app_admin_permission_edit',['id' => $permission->getId()]),
                    'delete' => $this->generateUrl('app_admin_permission_delete',['id' => $permission->getId()]),
                ]
            ];
        }

        return new JsonResponse(['data' => $data]);
    }
}
