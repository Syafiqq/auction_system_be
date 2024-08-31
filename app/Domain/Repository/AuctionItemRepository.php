<?php

namespace App\Domain\Repository;

use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemOwnedUserSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Throwable;

interface AuctionItemRepository
{
    /**
     * @param AuctionItemSearchRequestDto $searchQuery
     * @param int $page
     * @param int $itemPerPage
     * @return AbstractPaginator<AuctionItem>
     */
    public function findPaginatedFromLocal(
        AuctionItemSearchRequestDto $searchQuery,
        int                         $page,
        int                         $itemPerPage
    ): AbstractPaginator;

    /**
     * @param AuctionItemOwnedUserSearchRequestDto $searchQuery
     * @param int $page
     * @param int $itemPerPage
     * @return AbstractPaginator<AuctionItem>
     */
    public function findOwnedUserPaginatedFromLocal(
        AuctionItemOwnedUserSearchRequestDto $searchQuery,
        int                                  $page,
        int                                  $itemPerPage
    ): AbstractPaginator;

    /**
     * @param AuctionItemCreateRequestDto $data
     * @return AuctionItem
     */
    public function insertToLocal(AuctionItemCreateRequestDto $data): AuctionItem;

    /**
     * @param int $at
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     */
    public function findFromLocal(int $at): AuctionItem;

    /**
     * @param int $at
     * @param int $for
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     */
    public function findForFromLocal(int $at, int $for): AuctionItem;

    /**
     * @param AuctionItemUpdateRequestDto $data
     * @param int $at
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     */
    public function updateFromLocal(AuctionItemUpdateRequestDto $data, int $at): AuctionItem;

    /**
     * @param int $at
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     * @throws Throwable
     */
    public function deleteToLocal(int $at): AuctionItem;

    /**
     * @return mixed
     */
    public function findUndecidedWinnerFromLocal(): Collection;

    /**
     * @param AuctionItem $item
     * @param ?Bid $bid
     * @return AuctionItem
     */
    public function setWinnerToLocal(
        AuctionItem $item,
        ?Bid        $bid
    ): AuctionItem;

    /**
     * @param int $at
     * @param int $for
     * @param bool $to
     * @return AuctionItem
     * @throws ModelNotFoundException
     */
    public function updateAutobidToLocal(int $at, int $for, bool $to): AuctionItem;

    /**
     * @param int $at
     * @param int|null $except
     * @return array<int>
     */
    public function findAllAutobidUserFromLocal(int $at, ?int $except): array;
}
