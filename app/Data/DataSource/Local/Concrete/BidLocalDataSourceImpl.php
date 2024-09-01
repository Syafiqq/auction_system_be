<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\BidLocalDataSource;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Entity\User;
use Illuminate\Support\Collection;
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
    public function find(int $id): Bid
    {
        return Bid::query()->findOrFail($id);
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

    #[Override]
    public function getAutobidUsage(User $for, int $except): int
    {
        /** @var array<string> $auctionIds */
        $auctionIds = $for
            ->autobids()
            ->where('is_autobid', true)
            ->get()
            ->pluck('auction_item_id')
            ->toArray();
        $auctionIds = array_diff($auctionIds, [$except]);

        /** @var Collection $largestBids */
        $largestBids = Bid::whereIn('auction_item_id', $auctionIds)
            ->selectRaw('auction_item_id, MAX(amount) as amount, user_id')
            ->groupBy('auction_item_id')
            ->get();

        $sum = 0;
        foreach ($largestBids as $largestBid) {
            if ($largestBid->user_id === $for->id) {
                $sum += $largestBid->amount;
            }
        }

        return $sum;
    }
}
