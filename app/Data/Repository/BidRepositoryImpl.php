<?php

namespace App\Data\Repository;

use App\Data\DataSource\Local\Abstract\BidLocalDataSource;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Entity\User;
use App\Domain\Repository\BidRepository;
use Illuminate\Support\Collection;
use Override;

class BidRepositoryImpl implements BidRepository
{
    public function __construct(
        protected BidLocalDataSource $localDataSource,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insertToLocal(BidRequestDto $data, BidTypeEnum $type): Bid
    {
        return $this->localDataSource->insert($data, $type);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findFromLocal(int $id): Bid
    {
        return $this->localDataSource->find($id);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findWithUserFromLocal(int $id): Bid
    {
        return $this->localDataSource->findWithUser($id);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findNewestFromLocal(int $at): Bid
    {
        return $this->localDataSource->findNewest($at);
    }

    #[Override]
    public function getAutobidUsageFromLocal(User $for, int $except): int
    {
        return $this->localDataSource->getAutobidUsage($for, $except);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findUsersOfBidAuctionFromLocal(int $bidId): Collection
    {
        return $this->localDataSource->findUsersOfBidAuction($bidId);
    }
}
