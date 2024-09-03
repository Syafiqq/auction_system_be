<?php

namespace App\Data\DataSource\Local\Abstract;

use App\Domain\Entity\Bill;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface BillLocalDataSource
{
    /**
     * @param Bill $bill
     * @return Bill
     */
    public function insert(
        Bill $bill,
    ): Bill;

    /**
     * @param string $user_id
     * @param string $auction_item_id
     * @param string $bid_id
     * @return Bill|null
     */
    public function findBill(
        string $user_id,
        string $auction_item_id,
        string $bid_id
    ): ?Bill;

    /**
     * @param string $user_id
     * @param string $auction_item_id
     * @param string $bid_id
     * @return Bill
     * @throws ModelNotFoundException<Bill>
     */
    public function pay(
        string $user_id,
        string $auction_item_id,
        string $bid_id
    ): Bill;
}
