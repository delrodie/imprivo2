<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Permission;
use App\Form\PermissionType;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/permission')]
class PermissionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PermissionRepository $permissionRepository
    )
    {
    }

    #[Route('/', name:'app_admin_permission_index')]
    public function index(Request $request): Response
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        return $this->render('permission/index.html.twig', [
            'permission' => $permission,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_admin_permission_new', methods:['POST'])]
    public function new(Request $request)
    {
        $permission = new Permission();
        return $this->handleForm($request, $permission, true);
    }

    #[Route('/{id}/edit', name:'app_admin_permission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Permission $permission)
    {
        return $this->handleForm($request, $permission, false);
    }

    #[Route('/{id}', name:'app_admin_permission_delete', methods: ['DELETE'])]
    public function delete(Permission $permission): Response
    {
        $this->entityManager->remove($permission);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function handleForm(Request $request, Permission $permission, bool $isNew)
    {
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        // ✅ Cas GET → affichage initial du formulaire
        if ($request->isMethod('GET')) {
            return $this->render('permission/_form.html.twig', [
                'form' => $form->createView(),
                'permission' => $permission
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()){
            if ($isNew){
                $this->entityManager->persist($permission);
            }

            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'success' => false,
            'form' => $this->renderView('permission/_form.html.twig', [
                'form' => $form->createView(),
                'permission' => $permission
            ])
        ]);
    }
}
