<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WifiCode;

interface WifiCodeRepositoryInterface extends TransactionalRepositoryInterface {
    /**
     * @param User $user
     * @param int|null $offset
     * @param int $limit
     * @return WifiCode[]
     */
    public function findAllByUser(User $user, ?int $offset = 0, int $limit = 25): array;

    public function countAllByUser(User $user): int;

    /**
     * Counts all codes in the database
     *
     * @return int
     */
    public function countCodes(int $duration): int;

    /**
     * Counts all available codes
     *
     * @param int $duration The duration to count for
     * @return int
     */
    public function countAvailableCodes(int $duration): int;

    public function findNextAvailableCode(int $duration): ?WifiCode;

    public function codeExists(string $code): bool;

    /**
     * @return int[]
     */
    public function getAvailableDurations(): array;

    public function persist(WifiCode $code): void;

    public function remove(WifiCode $code): void;
}