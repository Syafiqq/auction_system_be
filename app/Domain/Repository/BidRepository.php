<?php

namespace App\Domain\Repository;

use App\Common\Exceptions\Bid\NewerBidPresentException;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Entity\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

interface BidRepository
{
    /**
     * @param BidRequestDto $data
     * @param BidTypeEnum $type
     * @return Bid
     * @throws NewerBidPresentException
     */
    public function insertToLocal(BidRequestDto $data, BidTypeEnum $type): Bid;

    /**
     * @param int $id
     * @return Bid
     * @throws ModelNotFoundException
     */
    public function findFromLocal(int $id): Bid;

    /**
     * @param int $id
     * @return Collection<Bid>
     */
    public function findLastTwoBidFromLocal(int $id): Collection;

    /**
     * @param int $id
     * @return Bid
     * @throws ModelNotFoundException
     */
    public function findWithUserFromLocal(int $id): Bid;

    /**
     * @param int $at
     * @return Bid
     * @throws ModelNotFoundException
     */
    public function findNewestFromLocal(int $at): Bid;

    /**
     * @param int $userId
     * @param int $auctionId
     * @return Bid|null
     */
    public function findLatestBidFromLocal(int $userId, int $auctionId): ?Bid;

    public function getAutobidUsageFromLocal(User $for, int $except): int;

    /**
     * @param int $bidId
     * @return Collection<User>
     * @throws ModelNotFoundException<Bid>
     */
    public function findUsersOfBidAuctionFromLocal(
        int $bidId,
    ): Collection;
}
