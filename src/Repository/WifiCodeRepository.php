<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WifiCode;

class WifiCodeRepository extends AbstractTransactionalRepository implements WifiCodeRepositoryInterface {

    public function findAllByUser(User $user, ?int $offset = 0, int $limit = 25): array {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(WifiCode::class, 'c')
            ->leftJoin('c.requestedBy', 'u')
            ->where('u.id = :user')
            ->orderBy('c.requestedAt', 'desc')
            ->setParameter('user', $user->getId())
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function countAllByUser(User $user): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(WifiCode::class, 'c')
            ->leftJoin('c.requestedBy', 'u')
            ->where('u.id = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countCodes(int $duration): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(WifiCode::class, 'c')
            ->where('c.duration = :duration')
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAvailableCodes(int $duration): int {
        return $this->em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from(WifiCode::class, 'c')
            ->where('c.requestedAt IS NULL')
            ->andWhere('c.duration = :duration')
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function persist(WifiCode $code): void {
        $this->em->persist($code);
        $this->flushIfNotInTransaction();
    }

    public function remove(WifiCode $code): void {
        $this->em->remove($code);
        $this->flushIfNotInTransaction();
    }

    public function findNextAvailableCode(int $duration): ?WifiCode {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(WifiCode::class, 'c')
            ->where('c.requestedAt IS NULL')
            ->andWhere('c.requestedBy IS NULL')
            ->andWhere('c.duration = :duration')
            ->setParameter('duration', $duration)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAvailableDurations(): array {
        $durations = $this->em->createQueryBuilder()
            ->select('DISTINCT(c.duration)')
            ->from(WifiCode::class, 'c')
            ->orderBy('c.duration', 'asc')
            ->getQuery()
            ->getScalarResult();

        return array_column($durations, 1);
    }

    public function codeExists(string $code): bool {
        $result = $this->em->createQueryBuilder()
            ->select('1')
            ->from(WifiCode::class, 'c')
            ->where('c.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();

        return $result !== null;
    }
}