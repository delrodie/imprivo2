<?php

namespace App\EventListener;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEntityListener(event: 'prePersist', method: 'prePersist', entity: Facture::class)]
#[AsEntityListener(event: 'preUpdate', method: 'preUpdate', entity: Facture::class)]
final class FactureListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function prePersist(Facture $facture, PrePersistEventArgs $args): void
    {
        $user = $this->security->getUser();
        if ($user) $facture->setCreatedBy($user->getUserIdentifier());
        $facture->setCreatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(Facture $facture, PreUpdateEventArgs $args): void
    {
        $user = $this->security->getUser();
        if ($user) $facture->setUpdatedBy($user->getUserIdentifier());
        $facture->setUpdatedAt(new \DateTimeImmutable());
    }
}
