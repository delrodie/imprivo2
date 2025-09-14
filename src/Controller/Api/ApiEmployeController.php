<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/employe')]
class ApiEmployeController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('api_employe/index.html.twig');
    }
}
