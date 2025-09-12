<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    #[Route('/', name:"app_admin_user_new", methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        return $this->render('admin_user/new.html.twig');
    }
}
