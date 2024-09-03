<?php

namespace App\Domain\Entity\Dto;

use App\Domain\Entity\Enum\BidStatusEnum;

class AuctionItemOwnedUserSearchRequestDto
{
    /**
     * @param string $userId
     * @param string|null $name
     * @param string|null $description
     * @param array<BidStatusEnum> $bidStatuses
     */
    public function __construct(
        public string  $userId,
        public ?string $name,
        public ?string $description,
        public array   $bidStatuses
    )
    {
    }
}
