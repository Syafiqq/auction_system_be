<?php

namespace App\Domain\UseCase\Concrete;

use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Bill;
use App\Domain\Entity\Enum\BillStatusEnum;
use App\Domain\Entity\Enum\NotificationType;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\Repository\BillRepository;
use App\Domain\Repository\InAppNotificationRepository;
use App\Domain\UseCase\Abstract\SetAuctionWinnerUseCase;
use App\Presentation\Jobs\AuctionWinnerMailPreparationJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use NumberFormatter;
use Override;

class SetAuctionWinnerUseCaseImpl implements SetAuctionWinnerUseCase
{
    public function __construct(
        protected BidRepository               $bidRepository,
        protected AuctionItemRepository       $auctionItemRepository,
        protected InAppNotificationRepository $inAppNotificationRepository,
        protected BillRepository              $billRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function execute(): void
    {
        /** @var Collection<AuctionItem> $auctions */
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
                $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                $this->inAppNotificationRepository->insertToLocal(
                    NotificationType::bidWinner->builder(
                        1,
                        [
                            'for' => $bid->user_id,
                            'at' => $bid->auction_item_id,
                            'name' => $auction->name,
                            'price' => $numberFormatter->formatCurrency($bid->amount, 'USD'),
                        ]
                    )
                );
            }

            $this->auctionItemRepository->setWinnerToLocal($auction, $bid);
            $this->billRepository->insertToLocal(new Bill([
                'user_id' => $bid->user_id,
                'auction_item_id' => $bid->auction_item_id,
                'bid_id' => $bid->id,
                'no' => $auction->idPadded(),
                'issued_at' => now(),
                'due_at' => now()->addDays(7),
                'paid_at' => null,
                'status' => BillStatusEnum::unpaid,
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            AuctionWinnerMailPreparationJob::dispatch($bid->id)
                ->onQueue('mailer');
        }
    }
}
