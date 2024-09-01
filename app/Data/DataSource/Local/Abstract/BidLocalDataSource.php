<?php

namespace App\Data\DataSource\Local\Abstract;


use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Entity\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

interface BidLocalDataSource
{
    /**
     * @param BidRequestDto $data
     * @param BidTypeEnum $type
     * @return Bid
     */
    public function insert(BidRequestDto $data, BidTypeEnum $type): Bid;

    /**
     * @param int $id
     * @return Bid
     * @throws ModelNotFoundException
     */
    public function find(int $id): Bid;

    /**
     * @param int $id
     * @return Bid
     * @throws ModelNotFoundException
     */
    public function findWithUser(int $id): Bid;

    /**
     * @param int $at
     * @return Bid
     * @throws ModelNotFoundException
     */
    public function findNewest(int $at): Bid;

    public function getAutobidUsage(User $for, int $except): int;

    /**
     * @param int $bidId
     * @return Collection<User>
     * @throws ModelNotFoundException<Bid>
     */
    public function findUsersOfBidAuction(
        int $bidId,
    ): Collection;
}
