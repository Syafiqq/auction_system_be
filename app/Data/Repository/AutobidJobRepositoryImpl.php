<?php

namespace App\Data\Repository;

use App\Data\DataSource\Local\Abstract\AutobidJobLocalDataSource;
use App\Domain\Entity\AutobidJob;
use App\Domain\Repository\AutobidJobRepository;
use Override;

class AutobidJobRepositoryImpl implements AutobidJobRepository
{
    public function __construct(
        protected AutobidJobLocalDataSource $localDataSource,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function massInsertToLocal(array $data): array
    {
        return $this->localDataSource->massInsert($data);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findFromLocal(int $at): ?AutobidJob
    {
        return $this->localDataSource->find($at);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function invalidateAllPreviousJobFromLocal(int $at): void
    {
        $this->localDataSource->invalidateAllPreviousJob($at);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateJobStatusFromLocal(int $at, bool $processed): void
    {
        $this->localDataSource->updateJobStatus($at, $processed);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function countProcessedSessionFromLocal(int $sessionId): array
    {
        return $this->localDataSource->countProcessedSession($sessionId);
    }
}
