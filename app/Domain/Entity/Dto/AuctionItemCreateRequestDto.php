<?php

namespace App\Domain\Entity\Dto;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class AuctionItemCreateRequestDto
{
    /**
     * @param string $name
     * @param string $description
     * @param int $startingPrice
     * @param Carbon $endTime
     * @param array<UploadedFile> $images
     * @param array<string> $imageNames
     */
    public function __construct(
        public string $name,
        public string $description,
        public int    $startingPrice,
        public Carbon $endTime,
        public array  $images,
        public array  $imageNames
    )
    {
    }
}
