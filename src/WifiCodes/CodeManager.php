<?php

namespace App\WifiCodes;

use App\Entity\User;
use App\Entity\WifiCode;
use App\Repository\WifiCodeRepositoryInterface;
use App\Security\Voter\CodeVoter;
use DateTime;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface;

class CodeManager {

    private static int $Threshold = 50;

    public function __construct(private readonly WifiCodeRepositoryInterface $repository, private readonly TokenStorageInterface $tokenStorage, private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    /**
     * @throws NoCodeAvailableException|NotGrantedException
     */
    public function requestCode(int $duration, ?string $comment): WifiCode {
        $code = $this->repository->findNextAvailableCode($duration);

        if($code === null) {
            throw new NoCodeAvailableException();
        }

        if($this->authorizationChecker->isGranted(CodeVoter::Request, $code) !== true) {
            throw new NotGrantedException();
        }

        $user = $this->tokenStorage->getToken()?->getUser();

        if(!$user instanceof User) {
            throw new NotGrantedException();
        }

        $code->setRequestedBy($user);
        $code->setRequestedAt(new DateTime());
        $code->setComment($comment);

        $this->repository->persist($code);
        return $code;
    }

    public function areAllCodesAlmostUsed(): bool {
        foreach($this->repository->getAvailableDurations() as $duration) {
            $available = $this->repository->countAvailableCodes($duration);


            if($available < self::$Threshold) {
                return true;
            }
        }

        return false;
    }
}