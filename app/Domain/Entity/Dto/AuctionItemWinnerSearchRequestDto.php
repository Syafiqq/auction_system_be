<?php

namespace App\Domain\Entity\Dto;

class AuctionItemWinnerSearchRequestDto
{
    /**
     * @param string $userId
     * @param string|null $name
     * @param string|null $description
     */
    public
    function __construct(
        public string  $userId,
        public ?string $name,
        public ?string $description,
    )
    {
    }
}
