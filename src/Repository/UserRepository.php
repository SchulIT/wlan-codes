<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository implements UserRepositoryInterface {

    public function findOneById(int $id): ?User {
        return $this->em->getRepository(User::class)
            ->findOneBy(['id' => $id ]);
    }

    public function findOneByUuid(string $uuid): ?User {
        return $this->em->getRepository(User::class)
            ->findOneBy([
                'uuid' => $uuid
            ]);
    }

    public function findOneByUsername(string $username): ?User {
        return $this->em->getRepository(User::class)
            ->findOneBy([
                'username' => $username
            ]);
    }

    public function persist(User $user): void {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function remove(User $user): void {
        $this->em->remove($user);
        $this->em->flush();
    }
}