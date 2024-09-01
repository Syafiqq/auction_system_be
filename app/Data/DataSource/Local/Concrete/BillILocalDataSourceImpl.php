<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\BillLocalDataSource;
use App\Domain\Entity\Bill;
use Override;

class BillILocalDataSourceImpl implements BillLocalDataSource
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insert(
        Bill $bill,
    ): Bill
    {
        $bill->save();
        return $bill;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findBill(
        string $user_id,
        string $auction_item_id,
        string $bid_id
    ): ?Bill
    {
        return Bill::query()
            ->where('user_id', $user_id)
            ->where('auction_item_id', $auction_item_id)
            ->where('bid_id', $bid_id)
            ->first();
    }
}
