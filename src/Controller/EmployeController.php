<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employe')]
class EmployeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmployeRepository $employeRepository,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    #[Route('/', name:'app_employe_index')]
    public function index(Request $request): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        return $this->render('employe/index.html.twig',[
            'employe' => $employe,
            'form' => $form
        ]);
    }

    #[Route('/new', name:'app_employe_new', methods:['GET','POST'])]
    public function new(Request $request): Response
    {
        $employe = new Employe();
        return $this->handleForm($request, $employe, true);
    }

    #[Route('/{id}', name:'app_employe_show', methods:['GET'])]
    public function show(Employe $employe)
    {

    }

    #[Route('/{id}/edit', name: 'app_employe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employe $employe)
    {
        return $this->handleForm($request, $employe, false);
    }

    #[Route('/{id}', name:'app_employe_delete', methods:['DELETE'])]
    public function delete(Employe $employe)
    {
        $this->entityManager->remove($employe);
        $this->entityManager->flush();


        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function handleForm(Request $request, Employe $employe, bool $isNew)
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        // ✅ Cas GET → affichage initial du formulaire
        if ($request->isMethod('GET')) {
            return $this->render('employe/_form.html.twig', [
                'form' => $form->createView(),
                'employe' => $employe
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $employe->getUser();
            // Mot de passe
            $plainPassword = $form->get('user')->get('password')->getData();
            if ($plainPassword){
                $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
            }

            if ($isNew){
                $this->entityManager->persist($employe);
            }

            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'success' => false,
            'form' => $this->renderView('employe/_form.html.twig', [
                'form' => $form->createView(),
                'employe' => $employe
            ])
        ]);
    }
}
