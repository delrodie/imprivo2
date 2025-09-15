<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Client;
use App\Enum\ClientType;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClientRepository $clientRepository
    )
    {
    }

    #[Route('/', name: 'app_client_index')]
    public function index(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);

        return $this->render('client/index.html.twig',[
            'client' => $client,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET','POST'])]
    public function new(Request $request): JsonResponse|Response
    {
        $client = new Client();
        return $this->handleForm($request, $client, true);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client)
    {

    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client): JsonResponse|Response
    {
        return $this->handleForm($request, $client, false);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['DELETE'])]
    public function delete(Client $client): Response
    {
        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function handleForm(Request $request, Client $client, bool $isNew): JsonResponse|Response
    {
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);

        // ✅ Cas GET → affichage initial du formulaire
        if ($request->isMethod('GET')) {
            return $this->render('client/_form.html.twig', [
                'form' => $form->createView(),
                'client' => $client
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            if ($isNew){
                $this->entityManager->persist($client);
            }

            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'success' => false,
            'form' => $this->renderView('client/_form.html.twig', [
                'form' => $form->createView(),
                'client' => $client
            ])
        ]);
    }
}
