<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    const EDIT = 'EDIT_USER';

    protected function supports(string $attribute, $subject)
    {
        return
            $attribute === self::EDIT &&
            $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if(
            !$user instanceof User ||
            !$subject instanceof User
        ) {
            return false;
        }

        return $subject->getId() ===  $user->getId();
    }
}