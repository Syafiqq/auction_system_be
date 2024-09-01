<?php

namespace App\Data\Repository;

use App\Data\DataSource\Local\Abstract\AuctionItemLocalDataSource;
use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemOwnedUserSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use App\Domain\Entity\Dto\AuctionItemWinnerRequestDto;
use App\Domain\Entity\Dto\AuctionItemWinnerSearchRequestDto;
use App\Domain\Repository\AuctionItemRepository;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
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
    public function findOwnedUserPaginatedFromLocal(
        AuctionItemOwnedUserSearchRequestDto $searchQuery,
        int                                  $page,
        int                                  $itemPerPage
    ): AbstractPaginator
    {
        return $this->localDataSource->findOwnedUserPaginated($searchQuery, $page, $itemPerPage);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findWinnerUserPaginatedFromLocal(
        AuctionItemWinnerSearchRequestDto $searchQuery,
        int                               $page,
        int                               $itemPerPage
    ): AbstractPaginator
    {
        return $this->localDataSource->findWinnerUserPaginated($searchQuery, $page, $itemPerPage);
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
    public function findForFromLocal(int $at, int $for): AuctionItem
    {
        return $this->localDataSource->findFor($at, $for);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findWinnerFromLocal(AuctionItemWinnerRequestDto $for): AuctionItem
    {
        return $this->localDataSource->findWinner($for);
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

    /**
     * @inheritDoc
     */
    #[Override]
    public function findUndecidedWinnerFromLocal(): Collection
    {
        return $this->localDataSource->findUndecidedWinners();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setWinnerToLocal(
        AuctionItem $item,
        ?Bid        $bid
    ): AuctionItem
    {
        return $this->localDataSource->setWinner($item, $bid);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateAutobidToLocal(int $at, int $for, bool $to): AuctionItem
    {
        return $this->localDataSource->updateAutobid($at, $for, $to);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findAllAutobidUserFromLocal(int $at, ?int $except): array
    {
        return $this->localDataSource->findAllAutobidUser($at, $except);
    }
}
