<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\BidLocalDataSource;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use Override;

class BidLocalDataSourceImpl implements BidLocalDataSource
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insert(BidRequestDto $data, BidTypeEnum $type): Bid
    {
        $builder = new Bid();
        $builder->user_id = $data->user->id;
        $builder->auction_item_id = $data->auctionItem;
        $builder->amount = $data->amount;
        $builder->type = $type;
        $builder->save();

        return $builder->refresh();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findNewest(int $at): Bid
    {
        return Bid::where('auction_item_id', $at)
            ->orderByDesc('amount')
            ->orderByDesc('bid_at')
            ->firstOrFail();
    }
}
