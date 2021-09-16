<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MissionVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === User::ROLE_ADMIN_MISSION;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return $user->hasRole([
            User::ROLE_ADMIN_MISSION,
            User::ROLE_SUPER_ADMIN
        ]);
    }
}
