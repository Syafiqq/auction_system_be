<?php

namespace App\Domain\Repository;

use App\Domain\Entity\AuctionWinnerMailJob;

interface AuctionWinnerMailJobRepository
{
    /**
     * @param array<AuctionWinnerMailJob> $data
     * @return array<string>
     */
    public function massInsertToLocal(
        array $data
    ): array;

    /**
     * @param int $at
     * @return AuctionWinnerMailJob|null
     */
    public function findFromLocal(int $at): ?AuctionWinnerMailJob;

    /**
     * @param int $at
     * @return void
     */
    public function deleteToLocal(int $at): void;
}
