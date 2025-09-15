<?php

namespace App\EventListener;

use App\Entity\Client;
use App\Entity\User;
use App\Services\UtilityService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEntityListener(event: 'prePersist', method: 'prePersist', entity: Client::class)]
#[AsEntityListener(event: 'preUpdate', method: 'preUpdate', entity: Client::class)]
final class ClientListener
{
    public function __construct(
        private UtilityService $utilityService,
        private Security $security
    )
    {
    }

    public function prePersist(Client $client, PrePersistEventArgs $args): void
    {
        // Generation du code client
        if (empty($client->getCode())){
            $client->setCode($this->utilityService->codeClient());
        }

        // Auteur creation
        $user = $this->security->getUser();
        if ($user){
            $client->setCreatedBy($user->getUserIdentifier());
        }

        // Date de creation
        $client->setCreatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(Client $client, PreUpdateEventArgs $args): void
    {
        // Generation du code client
        if (empty($client->getCode())){
            $client->setCode($this->utilityService->codeClient());
        }

        // Auteur creation
        $user = $this->security->getUser();
        if ($user){
            $client->setUpdatedBy($user->getUserIdentifier());
        }

        // Date de creation
        $client->setUpdatedAt(new \DateTimeImmutable());
    }
}
