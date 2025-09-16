<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\User;
use App\Entity\UserPermission;
use App\Repository\PermissionRepository;
use App\Repository\UserPermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user-permission')]
class UserPermissionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPermissionRepository $userPermissionRepository,
        private PermissionRepository $permissionRepository
    )
    {
    }

    #[Route('/{id}', name: 'admin_user_permissions', methods: ['GET'])]
    public function permissions(Employe $employe): Response
    {
        $userPermissionIds = array_map(
            fn(UserPermission $userPermission) => $userPermission->getPermission()->getId(),
            $employe->getUser()->getPermissions()->toArray()
        );

        $permissions = $this->permissionRepository->findAll();

        // Regrouper par module (ex: client, facture...)
        $grouped = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->getCode()); // ex: client_create
            $module = ucfirst($parts[0]);
            $action = $parts[1] ?? null;

            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][$action] = $permission;
        }


        return $this->render('admin_user/permissions.html.twig',[
            'employe' => $employe,
            'permissionsGrouped' => $grouped,
            'userPermissionIds' => $userPermissionIds
        ]);
    }

    #[Route('/{id}/toggle', name:'admin_user_permissions_toggle', methods: ['POST'])]
    public function toggle(Request $request, Employe $employe)
    {
        $permissionId = $request->request->get('permissionId');
        $enabled = filter_var($request->request->get('enabled'), FILTER_VALIDATE_BOOLEAN);

        // Cas classique : permission existante
        if ($permissionId) {
            $permission = $this->permissionRepository->find($permissionId);
            if (!$permission) {
                return $this->json([
                    'success' => false,
                    'message' => 'Permission introuvable'
                ], 404);
            }
        } else {
            // Cas nouveau : pas de permissionId → créer la permission
            $module = $request->request->get('module');
            $action = $request->request->get('action');

            if (!$module || !$action) {
                return $this->json([
                    'success' => false,
                    'message' => 'Module ou action manquant'
                ], 400);
            }

            // Vérifier si la permission existe déjà
            $permission = $this->permissionRepository->findOneBy([
                'module' => $module,
                'action' => $action,
            ]);

            if (!$permission) {
                $permission = new Permission();
                $permission->setModule($module);
                $permission->setAction($action);
                $this->entityManager->persist($permission);
                $this->entityManager->flush(); // nécessaire pour avoir un ID
            }
        }

        if ($enabled){
            // Ajout de la permission si pas déjà présente
            if (!$employe->getUser()->hasPermission($permission->getCode())) {
                $userPermission = new UserPermission();
                $userPermission->setPermission($permission);
                $userPermission->setUser($employe->getUser());
                $this->entityManager->persist($userPermission);
            }
        } else{
            // Suppression de la permission si elle existe
            $userPermission = $this->userPermissionRepository->findOneBy([
                'user' => $employe->getUser(),
                'permission' => $permission
            ]);
            if($userPermission){
                $this->entityManager->remove($userPermission);
            }
        }

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'permissionId' => $permission->getId() // renvoyé au Stimulus pour mettre à jour value
        ]);
    }

}
