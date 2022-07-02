<?php

namespace App\Security;

use App\Entity\Critique;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CritiqueVoter extends Voter
{
    const EDIT = 'EDIT_CRITIQUE';

    protected function supports(string $attribute, $subject)
    {
        return
            $attribute === self::EDIT &&
            $subject instanceof Critique;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if(
            !$user instanceof User ||
            !$subject instanceof Critique
        ) {
            return false;
        }

        return $subject->getUtilisateur()->getId() ===  $user->getId();
    }
}