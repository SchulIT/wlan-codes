<?php

namespace App\Twig;

use Exception;
use App\Entity\User;
use LightSaml\SpBundle\Security\Authentication\Token\SamlSpToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserVariable {
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    /**
     * @return SamlSpToken
     * @throws Exception
     */
    private function getToken() {
        $token = $this->tokenStorage->getToken();

        if(!$token instanceof SamlSpToken) {
            throw new Exception(sprintf('Token must be of type "%s" ("%s" given)', SamlSpToken::class, $token !== null ? $token::class : self::class));
        }

        return $token;
    }

    private function getUser(): User {
        $token = $this->getToken();
        $user = $token->getUser();

        if(!$user instanceof User) {
            throw new Exception(sprintf('Token must be of type "%s" ("%s" given)', User::class, $user::class));
        }

        return $user;
    }

    public function getFirstname() {
        return $this->getUser()->getFirstname();
    }

    public function getLastname() {
        return $this->getUser()->getLastname();
    }

    public function getEmailAddress() {
        return $this->getUser()->getEmail();
    }

    public function getServices() {
        return $this->getToken()->getAttribute('services');
    }
}