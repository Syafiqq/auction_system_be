<?php

namespace App\Data\Repository;

use App\Data\DataSource\Local\Abstract\AuctionItemLocalDataSource;
use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use App\Domain\Repository\AuctionItemRepository;
use Illuminate\Pagination\AbstractPaginator;
use Override;

class AuctionItemRepositoryImpl implements AuctionItemRepository
{
    public function __construct(
        protected AuctionItemLocalDataSource $localDataSource,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findPaginatedFromLocal(
        AuctionItemSearchRequestDto $searchQuery,
        int                         $page,
        int                         $itemPerPage
    ): AbstractPaginator
    {
        return $this->localDataSource->findPaginated($searchQuery, $page, $itemPerPage);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insertToLocal(AuctionItemCreateRequestDto $data): AuctionItem
    {
        $paths = $this->localDataSource->saveImages($data->images);
        $data->imageNames = $paths;
        return $this->localDataSource->insert($data);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findFromLocal(int $at): AuctionItem
    {
        return $this->localDataSource->find($at);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateFromLocal(AuctionItemUpdateRequestDto $data, int $at): AuctionItem
    {
        $paths = $this->localDataSource->saveImages($data->images);
        $data->imageNames = $paths;
        return $this->localDataSource->update($data, $at);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function deleteToLocal(int $at): AuctionItem
    {
        return $this->localDataSource->delete($at);
    }
}
