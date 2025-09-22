<?php

namespace App\Services;

use App\Entity\SequenceDoc;
use App\Repository\SequenceDocRepository;
use Doctrine\ORM\EntityManagerInterface;

class SequenceService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SequenceDocRepository $docRepository
    )
    {
    }

    public function generateNumero($type): array|string|null
    {
        $annee = (int) date('Y'); //dd($annee);
        $sequence = $this->docRepository->findOneBy(['type' => $type, 'annee' => $annee]);

        if (!$sequence){
            $sequence = new SequenceDoc();
            $sequence->setAnnee($annee);
            $sequence->setType($type);
            $sequence->setCompteur(0);
            $sequence->setPrefix($type->value);
            $this->entityManager->persist($sequence);
        }

        $sequence->setCompteur((int) $sequence->getCompteur()+1);
        $this->entityManager->flush();

        return str_replace(
            ['{prefix}', '{YYYY}', '{NNNN}'],
            [$sequence->getPrefix(), $annee, str_pad($sequence->getCompteur(), 4, '0', STR_PAD_LEFT)],
            $sequence->getFormat()
        );
    }
}
