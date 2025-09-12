<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/user')]
class ApiUserController extends AbstractController
{
    #[Route('/', name:'api_user_list', methods: ['GET'])]
    public function list(): JsonResponse
    {

    }
}
