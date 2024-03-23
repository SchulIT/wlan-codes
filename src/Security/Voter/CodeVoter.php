<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\WifiCode;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CodeVoter extends Voter {

    public const Show = 'show';

    public const Request = 'request';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager) {

    }

    protected function supports($attribute, $subject): bool {
        return in_array($attribute, [self::Show, self::Request]) && $subject instanceof WifiCode;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool {
        switch($attribute) {
            case self::Show:
                return $this->canShow($subject, $token);

            case self::Request:
                return $this->canRequest($subject, $token);
        }

        throw new LogicException('This code should not be executed.');
    }

    private function canRequest(WifiCode $code, TokenInterface $token): bool {
        $duration = $code->getDuration();
        $neededRole = sprintf('ROLE_%d', $duration);

        return $this->accessDecisionManager->decide($token, [ $neededRole ]);
    }

    private function canShow(WifiCode $code, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        return $code->getRequestedBy() !== null && $code->getRequestedBy()->getId() === $user->getId();
    }
}