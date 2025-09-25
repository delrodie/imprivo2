<?php

namespace App\Services;

use App\Entity\Devis;
use App\Entity\DevisLog;
use App\Enum\DevisStatut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DevisManager
{
    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function updateStatut(Devis $devis, DevisStatut $statut): void
    {
        $devis->setStatut($statut);

        $log = new DevisLog();
        $log->setDevis($devis);
        $log->setCreatedAt(new \DateTimeImmutable());
        $log->setAutheur($this->security->getUser()?->getUserIdentifier());

        $action = match ($statut){
            DevisStatut::ENVOYE => Action::DEVIS_ENVOYE,
            DevisStatut::ACCEPTE => Action::DEVIS_VALIDE,
            DevisStatut::REFUSE => Action::DEVIS_REFUSE,
            DevisStatut::TRANSFORME => Action::DEVIS_TRANSFORME,
            DevisStatut::BROUILLON => Action::DEVIS_BROUILLON,
            default => null,
        };

        $log->setAction($action);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
