<?php

namespace App\Domain\Entity\Dto;

use App\Domain\Entity\User;

class BidRequestDto
{
    /**
     * @param User $user
     * @param int $auctionItem
     * @param int $amount
     * @param int|null $lastBidReference
     */
    public function __construct(
        public User $user,
        public int  $auctionItem,
        public int  $amount,
        public ?int $lastBidReference,
    )
    {
    }
}
