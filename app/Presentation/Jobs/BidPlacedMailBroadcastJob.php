<?php

namespace App\Presentation\Jobs;

use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Presentation\Mail\BidPlacedMailer;
use App\Presentation\Mail\Presenter\BidPlacedMailPresenter;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\MailManager;
use Psr\Log\LoggerInterface;

class BidPlacedMailBroadcastJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param int $bidId
     */
    public function __construct(
        protected int $bidId,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(
        BidRepository         $bidRepository,
        AuctionItemRepository $auctionItemRepository,
        MailManager           $mailProvider,
        LoggerInterface       $logger,
    ): void
    {
        try {
            $bid = $bidRepository->findLastTwoBidFromLocal($this->bidId);
            if ($bid->count() <= 0) {
                $logger->error('[BidPlacedMailBroadcastJob] Can\'t find the specified bid', [
                    'bid_id' => $this->bidId,
                ]);
                return;
            }
            $auctionItem = $auctionItemRepository->findFromLocal($bid->first()->auction_item_id);
            $presenter = new BidPlacedMailPresenter($auctionItem, $bid);

            $users = $bidRepository->findUsersOfBidAuctionFromLocal($this->bidId);

            foreach ($users as $user) {
                $mailProvider->to($user->email, $user->username)
                    ->queue(new BidPlacedMailer($presenter));
            }
        } catch (Exception) {
            $logger->error('[BidPlacedMailBroadcastJob] Failed to broadcast bid placed mail', [
                'bid_id' => $this->bidId,
            ]);
        }
    }
}
