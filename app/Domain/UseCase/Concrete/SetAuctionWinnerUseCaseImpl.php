<?php

namespace App\Domain\UseCase\Concrete;

use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\UseCase\Abstract\SetAuctionWinnerUseCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Override;

class SetAuctionWinnerUseCaseImpl implements SetAuctionWinnerUseCase
{
    public function __construct(
        protected BidRepository         $bidRepository,
        protected AuctionItemRepository $auctionItemRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function execute(): void
    {
        $auctions = $this->auctionItemRepository->findUndecidedWinnerFromLocal();

        if ($auctions->isEmpty()) {
            return;
        }

        foreach ($auctions as $auction) {
            try {
                $bid = $this->bidRepository->findNewestFromLocal($auction->id);
            } catch (ModelNotFoundException) {
                $bid = null;
            }

            $this->auctionItemRepository->setWinnerToLocal($auction, $bid);
        }
    }
}
