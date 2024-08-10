<?php

namespace App\Domain\Repository;

use App\Common\Exceptions\Bid\NewerBidPresentException;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Entity\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * @param int $at
     * @return Bid
     * @throws ModelNotFoundException
     */
    public function findNewestFromLocal(int $at): Bid;

    public function getAutobidUsageFromLocal(User $for, int $except): int;
}
