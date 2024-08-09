<?php

namespace App\Domain\Repository;

use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\AbstractPaginator;
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
}
