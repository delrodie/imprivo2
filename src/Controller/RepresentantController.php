<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Representant;
use App\Form\RepresentantType;
use App\Repository\RepresentantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client-representant')]
class RepresentantController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RepresentantRepository $representantRepository
    )
    {
    }

    #[Route('/', name:'app_representant_index')]
    public function index(Request $request): Response
    {
        $representant = new Representant();
        $form = $this->createForm(RepresentantType::class, $representant);
        $form->handleRequest($request);

        return $this->render('client/representant.html.twig',[
            'representant' => $representant,
            'form' => $form
        ]);
    }

    #[Route('/new', name:'app_representant_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $representant = new Representant();
        return $this->handleForm($request, $representant, true);
    }

    #[Route('/{id}', name:'app_representant_show', methods: ['GET'])]
    public function show(Representant $representant)
    {

    }

    #[Route('/{id}/edit', name: 'app_representant_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Representant $representant): JsonResponse|Response
    {
        return $this->handleForm($request, $representant, false);
    }

    #[Route('/{id}', name: 'app_representant_delete', methods: ['DELETE'])]
    public function delete(Representant $representant): Response
    {
        $this->entityManager->remove($representant);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function handleForm(Request $request, Representant $representant, bool $isNew = false): JsonResponse|Response
    {
        $form = $this->createForm(RepresentantType::class, $representant);
        $form->handleRequest($request);

        // ✅ Cas GET → affichage initial du formulaire
        if ($request->isMethod('GET')) {
            return $this->render('client/_form_representant.html.twig', [
                'form' => $form->createView(),
                'representant' => $representant
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()){
            if ($isNew){
                $this->entityManager->persist($representant);
            }

            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'success' => false,
            'form' => $this->renderView('client/_form_representant.html.twig', [
                'form' => $form->createView(),
                'representant' => $representant
            ])
        ]);

    }
}
