<?php

namespace App\Autoconfig;

use App\Repository\WifiCodeRepositoryInterface;
use Override;
use SchulIT\CommonBundle\Autoconfig\Roles\RoleResolverInterface;

readonly class RoleResolver implements RoleResolverInterface {

    public function __construct(
        private WifiCodeRepositoryInterface $codeRepository
    ) {

    }

    #[Override]
    public function resolve(): array {
        $roles = [
            'ROLE_ADMIN',
            'ROLE_USER',
        ];

        foreach($this->codeRepository->getAvailableDurations() as $duration) {
            $roles[] = sprintf('ROLE_%d', $duration);
        }

        return $roles;
    }
}