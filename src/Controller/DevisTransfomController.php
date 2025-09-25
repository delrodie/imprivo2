<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\DevisLog;
use App\Enum\DevisStatut;
use App\Repository\DevisRepository;
use App\Services\Action;
use App\Services\DevisManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/devis-transfom')]
class DevisTransfomController extends AbstractController
{
    public function __construct(
        private readonly DevisRepository        $devisRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly DevisManager $devisManager
    )
    {
    }

    #[Route('/', name:'app_devistransfom_update', methods: ['GET','POST'])]
    public function update(Request $request): Response
    {
        $devisId = $request->request->get('devis_id');
        $btnSubmit = $request->request->get('devis_btn');
        $devis = $this->devisRepository->findOneBy(['id' => $devisId]);

        if(!$devis){
            return $this->json(['success' => false, 'error' => 'Devis introuvable'], 404);
        }

        if ($this->isCsrfTokenValid(
            'update'.$devis->getId(),
            $request->request->get('_token')
        )){
            match($btnSubmit){
                'envoyer' => $this->devisManager->updateStatut($devis, DevisStatut::ENVOYE),
                'accepter' => $this->devisManager->updateStatut($devis,DevisStatut::ACCEPTE),
                'refuser' => $this->devisManager->updateStatut($devis,DevisStatut::REFUSE),
                'transformer' => $this->devisManager->updateStatut($devis,DevisStatut::TRANSFORME),
                'brouillon' => $this->devisManager->updateStatut($devis,DevisStatut::BROUILLON),
                default => null,
            };

            $this->entityManager->flush();
        }


        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true, 'newStatut' => $devis->getStatut()->value]);
        }

        return $this->redirectToRoute('app_devis_index');
    }
}
