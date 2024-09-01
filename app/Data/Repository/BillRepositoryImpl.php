<?php

namespace App\Data\Repository;

use App\Data\DataSource\Local\Abstract\BillLocalDataSource;
use App\Domain\Entity\Bill;
use App\Domain\Repository\BillRepository;
use Override;

class BillRepositoryImpl implements BillRepository
{
    public function __construct(
        protected BillLocalDataSource $localDataSource,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insertToLocal(
        Bill $bill,
    ): Bill
    {
        return $this->localDataSource->insert($bill);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findBillFromLocal(
        string $user_id,
        string $auction_item_id,
        string $bid_id
    ): ?Bill
    {
        return $this->localDataSource->findBill($user_id, $auction_item_id, $bid_id);
    }
}
