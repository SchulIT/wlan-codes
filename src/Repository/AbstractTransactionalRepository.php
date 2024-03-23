<?php

namespace App\Repository;

abstract class AbstractTransactionalRepository extends AbstractRepository implements TransactionalRepositoryInterface {

    private bool $isTransactionActive = false;

    protected function flushIfNotInTransaction(): void {
        $this->isTransactionActive || $this->em->flush();
    }

    public function beginTransaction(): void {
        $this->em->beginTransaction();
        $this->isTransactionActive = true;
    }

    public function commit(): void {
        $this->em->flush();
        $this->em->commit();
        $this->isTransactionActive = false;
    }

    public function rollback(): void {
        $this->em->rollback();
        $this->isTransactionActive = false;
    }
}