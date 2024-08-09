<?php

namespace App\Domain\Entity\Dto;

class AuctionItemSearchRequestDto
{
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?bool   $isAsc,
    )
    {
    }
}
