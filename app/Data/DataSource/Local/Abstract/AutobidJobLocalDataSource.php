<?php

namespace App\Data\DataSource\Local\Abstract;


use App\Domain\Entity\AutobidJob;

interface AutobidJobLocalDataSource
{
    /**
     * @param array<AutobidJob> $data
     * @return array<string>
     */
    public function massInsert(
        array $data
    ): array;

    /**
     * @param int $at
     * @return AutobidJob|null
     */
    public function find(int $at): ?AutobidJob;

    /**
     * @param int $at
     * @return void
     */
    public function invalidateAllPreviousJob(int $at): void;

    /**
     * @param int $at
     * @param bool $processed
     * @return void
     */
    public function updateJobStatus(int $at, bool $processed): void;

    /**
     * @param int $sessionId
     * @return array
     */
    public function countProcessedSession(int $sessionId): array;
}
