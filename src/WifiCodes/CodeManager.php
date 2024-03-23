<?php

namespace App\WifiCodes;

use App\Entity\User;
use App\Entity\WifiCode;
use App\Repository\WifiCodeRepositoryInterface;
use DateTime;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CodeManager {

    private static $Threshold = 50;

    public function __construct(private WifiCodeRepositoryInterface $repository, private TokenStorageInterface $tokenStorage)
    {
    }

    public function requestCode(int $duration, ?string $comment): WifiCode {
        $code = $this->repository->findNextAvailableCode($duration);

        if($code === null) {
            throw new NoCodeAvailableException();
        }

        $token = $this->tokenStorage->getToken();

        if($token === null) {
            throw new RuntimeException('Token must not be null.');
        }

        $user = $token->getUser();

        if(!$user instanceof User) {
            throw new RuntimeException('User must be of instance User.');
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


            if($available < static::$Threshold) {
                return true;
            }
        }

        return false;
    }
}