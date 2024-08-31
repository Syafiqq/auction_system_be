<?php

namespace App\Data\DataSource\Local\Abstract;


use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\AuctionItemCreateRequestDto;
use App\Domain\Entity\Dto\AuctionItemOwnedUserSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemSearchRequestDto;
use App\Domain\Entity\Dto\AuctionItemUpdateRequestDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Throwable;

interface AuctionItemLocalDataSource
{
    /**
     * @param AuctionItemSearchRequestDto $searchQuery
     * @param int $page
     * @param int $itemPerPage
     * @return AbstractPaginator<AuctionItem>
     */
    public function findPaginated(
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
    public function findOwnedUserPaginated(
        AuctionItemOwnedUserSearchRequestDto $searchQuery,
        int                                  $page,
        int                                  $itemPerPage
    ): AbstractPaginator;

    /**
     * @param AuctionItemCreateRequestDto $data
     * @return AuctionItem
     */
    public function insert(AuctionItemCreateRequestDto $data): AuctionItem;

    /**
     * @param int $at
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     */
    public function find(int $at): AuctionItem;

    /**
     * @param int $at
     * @param int $for
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     */
    public function findFor(int $at, int $for): AuctionItem;

    /**
     * @param AuctionItemUpdateRequestDto $data
     * @param int $at
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     */
    public function update(AuctionItemUpdateRequestDto $data, int $at): AuctionItem;

    /**
     * @param int $at
     * @return AuctionItem
     * @throws ModelNotFoundException<AuctionItem>
     * @throws Throwable
     */
    public function delete(int $at): AuctionItem;

    /**
     * @param array<UploadedFile> $data
     * @return array<string>
     */
    public function saveImages(
        array $data,
    ): array;

    /**
     * @return Collection
     */
    public function findUndecidedWinners(): Collection;

    /**
     * @param AuctionItem $item
     * @param ?Bid $bid
     * @return AuctionItem
     */
    public function setWinner(
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
    public function updateAutobid(int $at, int $for, bool $to): AuctionItem;

    /**
     * @param int $at
     * @param int|null $except
     * @return array<int>
     */
    public function findAllAutobidUser(int $at, ?int $except): array;
}
