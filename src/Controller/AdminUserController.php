<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
class AdminUserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    #[ROute('/', name:'app_admin_user_index')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        return $this->render('admin_user/new.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/new', name:"app_admin_user_new", methods: ['GET','POST'])]
    public function new(Request $request)
    {
        $user = new User();
        return $this->handleForm($request, $user, true);
    }

    #[Route('/{id}', name:"app_admin_user_show", methods: ['GET'])]
    public function details()
    {

    }

    #[Route('/{id}/edit', name:"app_admin_user_edit", methods: ['GET','POST'])]
    public function edit(Request $request, User $user)
    {
        return $this->handleForm($request, $user, false);
    }

    #[Route('/{id}', name: "app_admin_user_delete", methods: ['DELETE'])]
    public function delete(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();


        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function handleForm(Request $request, User $user, bool $isNew)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // ✅ Cas GET → affichage initial du formulaire
        if ($request->isMethod('GET')) {
            return $this->render('admin_user/_form.html.twig', [
                'form' => $form->createView(),
                'user' => $user
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword()) {
                $user->setPassword(
                    $this->passwordHasher->hashPassword($user, $user->getPassword())
                );
            }
            if ($isNew) {
                $this->entityManager->persist($user);
            }
            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'success' => false,
            'form' => $this->renderView('admin_user/_form.html.twig', [
                'form' => $form->createView(),
                'user' => $user
            ])
        ]);
    }

}
