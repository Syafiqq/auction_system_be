<?php

namespace App\Data\DataSource\Local\Abstract;


use App\Domain\Entity\AuctionWinnerMailJob;

interface AuctionWinnerMailJobLocalDataSource
{
    /**
     * @param array<AuctionWinnerMailJob> $data
     * @return array<string>
     */
    public function massInsert(
        array $data
    ): array;

    /**
     * @param int $at
     * @return AuctionWinnerMailJob|null
     */
    public function find(int $at): ?AuctionWinnerMailJob;

    /**
     * @param int $at
     * @return void
     */
    public function delete(int $at): void;
}
