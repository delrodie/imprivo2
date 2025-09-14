<?php

namespace App\EventListener;

use App\Entity\Employe;
use App\Services\UtilityService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: 'prePersist', method: 'prePersist', entity: Employe::class)]
#[AsEntityListener(event: 'preUpdate', method: 'preUpdate', entity: Employe::class)]
final class EmployeListener
{
    public function __construct(
        private UtilityService $utilityService,
        private Security $security,
    )
    {
    }

    public function prePersist(Employe $employe, PrePersistEventArgs $args): void
    {
        // Génération du code employé
        if(empty($employe->getCode())){
            $employe->setCode($this->utilityService->codeEmploye());
        }

        // Auteur création
        $user = $this->security->getUser();
        if($user){
            $employe->setCreatedBy($user->getUserIdentifier());
        }
    }

    public function preUpdate(Employe $employe, PreUpdateEventArgs $args): void
    {
        // Auteur modification
        $user = $this->security->getUser();
        if ($user){
            $employe->setCreatedBy($user->getUserIdentifier());
        }
    }
}
