<?php

namespace App\Services;

use App\Entity\Devis;
use App\Entity\DevisLog;
use App\Entity\Facture;
use App\Entity\FactureLigne;
use App\Enum\DevisStatut;
use App\Enum\FactureStatut;
use App\Enum\SequenceDocType;
use App\Services\SequenceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class DevisManager
{
    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager, private readonly SequenceService $sequenceService)
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

        // Creation de la facture correspondante
        if ($statut === DevisStatut::TRANSFORME){
            $this->transformToFacture($devis);
        }

        $this->entityManager->flush();
    }

    public function transformToFacture(Devis $devis): void
    {
        $facture = new Facture();
        $facture->setNumero($this->sequenceService->generateNumero(SequenceDocType::FAC));
        $facture->setDevis($devis);
        $facture->setClient($devis->getClient());
        $facture->setTotalHT($devis->getTotalHT());
        $facture->setTauxTVA($devis->getTauxTVA());
        $facture->setTotalTVA($devis->getTotalTVA());
        $facture->setTotalTTC($devis->getTotalTTC());
        $facture->setRemise($facture->getRemise());
        $facture->setStatut(FactureStatut::BROUILLON);
        $facture->setDate(new  \DateTime());
        foreach ($devis->getLignes() as $ligne){
            $factureLigne = new FactureLigne();
            $factureLigne->setDesignation($ligne->getDesignation());
            $factureLigne->setPrixUnitaire($ligne->getPrixUnitaire());
            $factureLigne->setQuantite($ligne->getQuantite());
            $factureLigne->setUom($ligne->getUom());
            $factureLigne->setMontant($ligne->getMontant());
            $factureLigne->setDetais($ligne->getDetails());
            $factureLigne->setFacture($facture);

            $this->entityManager->persist($factureLigne);
        }

        $this->entityManager->persist($facture);
    }
}
