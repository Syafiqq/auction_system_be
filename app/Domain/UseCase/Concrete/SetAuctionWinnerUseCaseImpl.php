<?php

namespace App\Domain\UseCase\Concrete;

use App\Domain\Entity\Enum\NotificationType;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\Repository\InAppNotificationRepository;
use App\Domain\UseCase\Abstract\SetAuctionWinnerUseCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Override;

class SetAuctionWinnerUseCaseImpl implements SetAuctionWinnerUseCase
{
    public function __construct(
        protected BidRepository               $bidRepository,
        protected AuctionItemRepository       $auctionItemRepository,
        protected InAppNotificationRepository $inAppNotificationRepository,
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

            if ($bid != null) {
                $this->inAppNotificationRepository->insertToLocal(
                    NotificationType::bidWinner->builder(
                        1,
                        [
                            'for' => $bid->user_id,
                            'at' => $bid->auction_item_id,
                            'name' => $auction->name,
                            'price' => $bid->amount,
                        ]
                    )
                );
            }

            $this->auctionItemRepository->setWinnerToLocal($auction, $bid);
        }
    }
}
