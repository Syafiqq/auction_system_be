<?php

namespace App\Domain\Repository;

use App\Domain\Entity\AutobidJob;

interface AutobidJobRepository
{
    /**
     * @param array<AutobidJob> $data
     * @return array<string>
     */
    public function massInsertToLocal(
        array $data
    ): array;

    /**
     * @param int $at
     * @return AutobidJob|null
     */
    public function findFromLocal(int $at): ?AutobidJob;

    /**
     * @param int $at
     * @return void
     */
    public function invalidateAllPreviousJobFromLocal(int $at): void;

    /**
     * @param int $at
     * @param bool $processed
     * @return void
     */
    public function updateJobStatusFromLocal(int $at, bool $processed): void;

    /**
     * @param int $sessionId
     * @return array
     */
    public function countProcessedSessionFromLocal(int $sessionId): array;
}
