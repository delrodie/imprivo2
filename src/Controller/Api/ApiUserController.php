<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/user')]
class ApiUserController extends AbstractController
{
    #[Route('/', name:'api_user_list', methods: ['GET'])]
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $data=[];
        foreach ($users as $index => $user) {
            $data[] = [
                'id' => $index +1,
                'nom' => '-',
                'services' => '-',
                'username' => $user->getUserIdentifier(),
                'connexion' => $user->getConnexion(),
                'lastConnectedAt' => $user->getLastConnectedAt() ? $user->getLastConnectedAt()->format('Y-m-d H:i:s') : '-',
                'actions' => [
                    'detail' => $this->generateUrl('app_admin_user_show',['id' => $user->getId()]),
                    'edit' => $this->generateUrl('app_admin_user_edit',['id' => $user->getId()]),
                    'delete' => $this->generateUrl('app_admin_user_delete',['id' => $user->getId()]),
                ]
            ];
        }

        return new JsonResponse(['data' => $data]);
    }
}
