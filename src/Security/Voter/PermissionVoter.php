<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;

final class PermissionVoter extends Voter
{
//    public const EDIT = 'CLIENT_EDIT';
//    public const VIEW = 'CLIENT_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return is_string($attribute);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Si l’utilisateur n’est pas connecté, on refuse
        if (!$user instanceof UserInterface) {
            return false;
        }

        // SuperAdmin en mémoire : on autorise tout
        if ($user instanceof InMemoryUser && in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // Utilisateur normal (entité User) : on vérifie la permission
        if ($user instanceof User) {
            return $user->hasPermission($attribute);
        }

        // Tout autre type d’utilisateur : refus par défaut
        return false;
    }
}
