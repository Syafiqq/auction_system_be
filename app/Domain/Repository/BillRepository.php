<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Bill;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface BillRepository
{
    /**
     * @param Bill $bill
     * @return Bill
     */
    public function insertToLocal(
        Bill $bill,
    ): Bill;

    /**
     * @param string $user_id
     * @param string $auction_item_id
     * @param string $bid_id
     * @return Bill|null
     */
    public function findBillFromLocal(
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
    public function payToLocal(
        string $user_id,
        string $auction_item_id,
        string $bid_id
    ): Bill;
}
