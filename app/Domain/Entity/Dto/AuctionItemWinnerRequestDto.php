<?php

namespace App\Domain\Entity\Dto;

class AuctionItemWinnerRequestDto
{
    /**
     * @param string $userId
     * @param string $auctionId
     */
    public
    function __construct(
        public string $userId,
        public string $auctionId,
    )
    {
    }
}
