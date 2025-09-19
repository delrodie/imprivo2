<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UniteMesure;
use App\Form\UniteMesureType;
use App\Repository\UniteMesureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/unite-mesure')]
class UniteMesureController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UniteMesureRepository $uniteMesureRepository,
    )
    {
    }

    #[Route('/', name: 'app_unitemesure_index')]
    public function index(Request $request): Response
    {
        $uniteMesure = new UniteMesure();
        $form = $this->createForm(UniteMesureType::class, $uniteMesure);
        $form->handleRequest($request);

        return $this->render('parametres/unite_mesure.html.twig',[
            'unite_mesure' => $uniteMesure,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_unitemesure_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $uniteMesure = new UniteMesure();
        return $this->handleForm($request, $uniteMesure, true);
    }

    #[Route('/{id}', name: 'app_unitemesure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UniteMesure $uniteMesure)
    {
        return $this->handleForm($request, $uniteMesure);
    }

    #[Route('/{id}', name:'app_unitemesure_delete', methods: ['DELETE'])]
    public function delete(UniteMesure $uniteMesure): Response
    {
        $this->entityManager->remove($uniteMesure);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function handleForm(Request $request, UniteMesure $uniteMesure, bool $isNew = false): Response
    {
        $form = $this->createForm(UniteMesureType::class, $uniteMesure);
        $form->handleRequest($request);

        // ✅ Cas GET → affichage initial du formulaire
        if ($request->isMethod('GET')) {
            return $this->render('parametres/_form_unite_mesure.html.twig', [
                'form' => $form->createView(),
                'unite_mesure' => $uniteMesure
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()){
            if ($isNew){
                $this->entityManager->persist($uniteMesure);
            }
            $this->entityManager->flush();

            $uniteMesure = new UniteMesure(); // nouveau formulaire vierge
            $form = $this->createForm(UniteMesureType::class, $uniteMesure);

            return new JsonResponse([
                'success' => true,
                'form' => $this->renderView('parametres/_form_unite_mesure.html.twig', [
                    'form' => $form->createView(),
                    'unite_mesure' => $uniteMesure
                ])
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'form' => $this->renderView('parametres/_form_unite_mesure.html.twig', [
                'form' => $form->createView(),
                'unite_mesure' => $uniteMesure
            ])
        ]);
    }
}
