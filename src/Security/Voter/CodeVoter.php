<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\WifiCode;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CodeVoter extends Voter {

    public const Show = 'show';

    protected function supports($attribute, $subject): bool {
        return $attribute === static::Show && $subject instanceof WifiCode;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        if(!$subject instanceof WifiCode) {
            throw new LogicException('This code should not be executed.');
        }

        return $subject->getRequestedBy() !== null && $subject->getRequestedBy()->getId() === $user->getId();
    }
}