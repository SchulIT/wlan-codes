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
    public function findAllByUser(User $user, ?int $offset = 0, $limit = 25): array;

    /**
     * @param User $user
     * @return int
     */
    public function countAllByUser(User $user): int;

    /**
     * Counts all codes in the database
     *
     * @param int $duration
     * @return int
     */
    public function countCodes(int $duration): int;

    /**
     * Counts all available codes
     *
     * @param int $duration
     * @return int
     */
    public function countAvailableCodes(int $duration): int;

    /**
     * @param int $duration
     * @return WifiCode|null
     */
    public function findNextAvailableCode(int $duration): ?WifiCode;

    /**
     * @return int[]
     */
    public function getAvailableDurations(): array;

    /**
     * @param WifiCode $code
     */
    public function persist(WifiCode $code): void;

    /**
     * @param WifiCode $code
     */
    public function remove(WifiCode $code): void;

    /**
     * Detach all codes entity from the entity manager
     */
    public function detach(): void;
}