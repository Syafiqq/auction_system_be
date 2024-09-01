<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\BillLocalDataSource;
use App\Domain\Entity\Bill;
use App\Domain\Entity\Enum\BillStatusEnum;
use Illuminate\Support\Carbon;
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

    /**
     * @inheritDoc
     */
    #[Override]
    public function pay(string $user_id, string $auction_item_id, string $bid_id): Bill
    {
        /** @var Bill $builder */
        $builder = Bill::query()
            ->where('user_id', $user_id)
            ->where('auction_item_id', $auction_item_id)
            ->where('bid_id', $bid_id)
            ->firstOrFail();

        $builder->status = BillStatusEnum::paid;
        $builder->paid_at = Carbon::now();
        $builder->save();

        return $builder;
    }
}
