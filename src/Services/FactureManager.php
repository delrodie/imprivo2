<?php

namespace App\Services;

use App\Entity\Facture;
use App\Entity\FactureLog;
use App\Enum\FactureStatut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class FactureManager
{
    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function updateStatut(Facture $facture, FactureStatut $statut): void
    {
        $facture->setstatut($statut);

        $log = new FactureLog();
        $log->setFacture($facture);
        $log->setCreatedAt(new \DateTimeImmutable());
        $log->setAuteur($this->security->getUser()->getUserIdentifier());

        $action = match ($statut){
            FactureStatut::BROUILLON => Action::FACTURE_BROUILLON,
            FactureStatut::VALIDEE => Action::FACTURE_VALIDE,
            FactureStatut::ANNULEE => Action::FACTURE_ANNULEE,
            FactureStatut::PAYEE => Action::FACTURE_PAYEE,
            FactureStatut::IMPAYEE => Action::FACTURE_IMPAYEE,
            FactureStatut::PARTIELLEMENT_PAYEE => Action::FACTURE_PARTIELLE,
            default => null,
        };

        $log->setAction($action);
        $this->entityManager->persist($log);
    }
}
