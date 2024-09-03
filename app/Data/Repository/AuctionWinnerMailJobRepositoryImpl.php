<?php

namespace App\Data\Repository;

use App\Data\DataSource\Local\Abstract\AuctionWinnerMailJobLocalDataSource;
use App\Domain\Entity\AuctionWinnerMailJob;
use App\Domain\Repository\AuctionWinnerMailJobRepository;
use Override;

class AuctionWinnerMailJobRepositoryImpl implements AuctionWinnerMailJobRepository
{
    public function __construct(
        protected AuctionWinnerMailJobLocalDataSource $localDataSource,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override] public function massInsertToLocal(array $data): array
    {
        return $this->localDataSource->massInsert($data);
    }

    /**
     * @inheritDoc
     */
    #[Override] public function findFromLocal(int $at): ?AuctionWinnerMailJob
    {
        return $this->localDataSource->find($at);
    }

    /**
     * @inheritDoc
     */
    #[Override] public function deleteToLocal(int $at): void
    {
        $this->localDataSource->delete($at);
    }
}
