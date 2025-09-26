<?php

namespace App\EventListener;

use App\Entity\Representant;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEntityListener(event: 'prePersist', method: 'prePersist', entity: Representant::class)]
#[AsEntityListener(event: 'preUpdate', method: 'preUpdate', entity: Representant::class)]
final class RepresentantListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function prePersist(Representant $representant, PrePersistEventArgs $args): void
    {
        $user = $this->security->getUser();
        if ($user) $representant->setCreatedBy($user->getUserIdentifier());

        $representant->setCreatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(Representant $representant, PreUpdateEventArgs $args): void
    {
        $user = $this->security->getUser();
        if ($user) $representant->setUpdatedBy($user->getUserIdentifier());

        $representant->setUpdatedAt(new \DateTimeImmutable());
    }
}
