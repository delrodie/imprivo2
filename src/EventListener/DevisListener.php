<?php

namespace App\EventListener;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEntityListener(event: 'prePersist', method: 'prePersist', entity: Devis::class)]
#[AsEntityListener(event: 'preUpdate', method: 'preUpdate', entity: Devis::class)]
final class DevisListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function prePersist(Devis $devis, PrePersistEventArgs $args): void
    {
        $user = $this->security->getUser();
        if ($user) $devis->setCreatedBy($user);

        $devis->setCreatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(Devis $devis, PreUpdateEventArgs $args): void
    {
        $user = $this->security->getUser();
        if ($user) $devis->setUpdatedBy($user);

        $devis->setUpdatedAt(new \DateTimeImmutable());
    }
}
