<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\AuctionItemLocalDataSource;
use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\AuctionItemImage;
use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use Illuminate\Pagination\AbstractPaginator;
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

        $nameQuery = $searchQuery->name;
        if ($nameQuery !== null && $nameQuery !== '') {
            $query->where('name', 'like', '%' . $nameQuery . '%');
        }

        $descriptionQuery = $searchQuery->description;
        if ($descriptionQuery !== null && $descriptionQuery !== '') {
            $query->where('description', 'like', '%' . $descriptionQuery . '%');
        }

        $isAsc = $searchQuery->isAsc;
        if ($isAsc !== null) {
            $query->orderBy('starting_price', $isAsc ? 'asc' : 'desc');
        }

        return $query->with('images')->paginate($itemPerPage, page: $page);
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
}
