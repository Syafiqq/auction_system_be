<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\AuctionItemLocalDataSource;
use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\AuctionItemImage;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemOwnedUserSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use App\Domain\Entity\Dto\AuctionItemWinnerSearchRequestDto;
use App\Domain\Entity\Enum\BidStatusEnum;
use App\Domain\Entity\UserAuctionAutobid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Override;
use Ramsey\Uuid\Uuid;

class AuctionItemLocalDataSourceImpl implements AuctionItemLocalDataSource
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findPaginated(
        AuctionItemSearchRequestDto $searchQuery,
        int                         $page,
        int                         $itemPerPage
    ): AbstractPaginator
    {
        $query = AuctionItem::query();

        $query->select('auction_items.*', DB::raw('COALESCE(MAX(bids.amount), auction_items.starting_price) AS total_bid_amount'))
            ->leftJoin('bids', 'auction_items.id', '=', 'bids.auction_item_id');

        $this->_queryAuctionItemSearchByName($query, $searchQuery->name, $searchQuery->description);

        $isAsc = $searchQuery->isAsc;
        if ($isAsc !== null) {
            $query->orderBy('total_bid_amount', $isAsc ? 'asc' : 'desc');
        }

        return $query
            ->groupBy('auction_items.id')
            ->with('images')
            ->paginate($itemPerPage, page: $page);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findOwnedUserPaginated(
        AuctionItemOwnedUserSearchRequestDto $searchQuery,
        int                                  $page,
        int                                  $itemPerPage
    ): AbstractPaginator
    {
        $query = AuctionItem::query();

        $query
            ->select('auction_items.*')
            ->leftJoinSub('SELECT auction_item_id, user_id, MAX(id) AS latest_bid, bid_at FROM bids GROUP BY auction_item_id',
                'bids1',
                'auction_items.id',
                '=',
                'bids1.auction_item_id'
            );

        $this->_queryAuctionItemSearchByName($query, $searchQuery->name, $searchQuery->description);

        $query->where(function ($query) use ($searchQuery) {
            $query->where(false);
            foreach ($searchQuery->bidStatuses as $status) {
                switch ($status) {
                    case BidStatusEnum::win:
                        $query->orWhere(function ($query) use ($searchQuery) {
                            $query->where('auction_items.has_winner', true)
                                ->where('bids1.user_id', $searchQuery->userId);
                        });
                        break;
                    case BidStatusEnum::lose:
                        $query->orWhere(function ($query) use ($searchQuery) {
                            $query->where('auction_items.has_winner', true)
                                ->where('bids1.user_id', '!=', $searchQuery->userId);
                        });
                        break;
                    case BidStatusEnum::inProgressLeading:
                        $query->orWhere(function ($query) use ($searchQuery) {
                            $query->where('auction_items.has_winner', false)
                                ->where('bids1.user_id', $searchQuery->userId);
                        });
                        break;
                    case BidStatusEnum::inProgress:
                        $query->orWhere(function ($query) use ($searchQuery) {
                            $query->where('auction_items.has_winner', false)
                                ->where('bids1.user_id', '!=', $searchQuery->userId);
                        });
                        break;
                }
            }
        });
        $query->orderBy('bids1.bid_at', 'desc');

        return $query
            ->with('images')
            ->paginate($itemPerPage, page: $page);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findWinnerUserPaginated(
        AuctionItemWinnerSearchRequestDto $searchQuery,
        int                               $page,
        int                               $itemPerPage
    ): AbstractPaginator
    {
        $query = AuctionItem::query();

        $query
            ->select('auction_items.*')
            ->leftJoin('bids', 'auction_items.winner_id', '=', 'bids.id');

        $this->_queryAuctionItemSearchByName($query, $searchQuery->name, $searchQuery->description);

        $query->where('bids.user_id', $searchQuery->userId);

        return $query
            ->with('bill')
            ->with('winnerUser')
            ->with('images')
            ->paginate($itemPerPage, page: $page);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insert(AuctionItemCreateRequestDto $data): AuctionItem
    {
        $builder = new AuctionItem();
        $builder->name = $data->name;
        $builder->description = $data->description;
        $builder->starting_price = $data->startingPrice;
        $builder->end_time = $data->endTime;
        $builder->save();

        $builder->images()->saveMany(
            array_map(
                function ($name) {
                    $image = new AuctionItemImage();
                    $image->name = $name;
                    return $image;
                },
                $data->imageNames
            )
        );

        return $builder->load('images');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function find(int $at): AuctionItem
    {
        return AuctionItem::with('images')->findOrFail($at);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findFor(int $at, int $for): AuctionItem
    {
        return AuctionItem::with('images', 'autobids')
            ->with(['autobids' => function ($query) use ($for) {
                $query->where('user_id', $for);
            }])
            ->findOrFail($at);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function update(AuctionItemUpdateRequestDto $data, int $at): AuctionItem
    {
        /** @var AuctionItem $builder */
        $builder = AuctionItem::with('images')->findOrFail($at);

        $builder->name = $data->name;
        $builder->description = $data->description;
        $builder->starting_price = $data->startingPrice;
        $builder->end_time = $data->endTime;
        $builder->save();

        $builder->images()
            ->whereNotIn('id', $data->retainedOldImageIds)
            ->delete();

        $builder->images()->saveMany(
            array_map(
                function ($name) {
                    $image = new AuctionItemImage();
                    $image->name = $name;
                    return $image;
                },
                $data->imageNames
            )
        );

        return $builder->load('images');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function delete(int $at): AuctionItem
    {
        $builder = AuctionItem::with('images')->findOrFail($at);
        $builder->deleteOrFail();
        return $builder;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function saveImages(
        array $data,
    ): array
    {
        $imageNames = [];
        foreach ($data as $image) {
            $imageName = Uuid::uuid7()->toString() . '-' . $image->getClientOriginalName();
            $image->storeAs(AuctionItemImage::$publicStoragePath, $imageName, 'public');
            $imageNames[] = $imageName;
        }
        return $imageNames;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findUndecidedWinners(): Collection
    {
        return AuctionItem::query()
            ->where('has_winner', false)
            ->where('end_time', '<', now())
            ->get();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setWinner(
        AuctionItem $item,
        ?Bid        $bid
    ): AuctionItem
    {
        $item->has_winner = true;
        $item->winner_id = $bid?->id;
        $item->save();

        return $item->load('images');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateAutobid(int $at, int $for, bool $to): AuctionItem
    {
        /** @var AuctionItem $builder */
        $builder = AuctionItem::findOrFail($at);

        /** @var UserAuctionAutobid $autobid */
        $autobid = $builder
            ->autobids()
            ->where('user_id', $for)
            ->firstOrCreate(['user_id' => $for]);
        $autobid->is_autobid = $to;
        $autobid->save();

        return $builder->load(['autobids' => function ($query) use ($for) {
            $query->where('user_id', $for);
        }]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findAllAutobidUser(int $at, ?int $except): array
    {
        $query = UserAuctionAutobid::query()
            ->where('auction_item_id', $at)
            ->where('is_autobid', true);

        if ($except !== null) {
            $query = $query->
            where('user_id', '!=', $except);
        }

        return $query->pluck('user_id')
            ->toArray();
    }


    /**
     * @param Builder<AuctionItem> $query
     * @param string|null $name
     * @param string|null $description
     * @return void
     *
     * @visibleForTesting
     */
    private function _queryAuctionItemSearchByName(
        Builder $query,
        ?string $name,
        ?string $description
    ): void
    {
        if ($name !== null && $name !== '') {
            $query->where('auction_items.name', 'like', '%' . $name . '%');
        }

        if ($description !== null && $description !== '') {
            $query->where('auction_items.description', 'like', '%' . $description . '%');
        }
    }
}
