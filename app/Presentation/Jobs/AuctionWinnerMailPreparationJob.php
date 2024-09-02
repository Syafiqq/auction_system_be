<?php

namespace App\Presentation\Jobs;

use App\Domain\Entity\AuctionWinnerMailJob;
use App\Domain\Repository\AuctionWinnerMailJobRepository;
use App\Domain\Repository\BidRepository;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Psr\Log\LoggerInterface;

class AuctionWinnerMailPreparationJob implements ShouldQueue
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
        BidRepository                  $bidRepository,
        AuctionWinnerMailJobRepository $mailJobRepository,
        LoggerInterface                $logger,
    ): void
    {
        try {
            $bid = $bidRepository->findFromLocal($this->bidId);
            $usersIds = $bidRepository->findUsersOfBidAuctionFromLocal($this->bidId)->pluck('id')->toArray();

            $jobs = array_map(
                fn($userId) => new AuctionWinnerMailJob([
                    'bid_id' => $this->bidId,
                    'user_id' => $userId,
                    'auction_item_id' => $bid->auction_item_id,
                ]),
                $usersIds
            );

            if (empty($jobs)) {
                return;
            }

            $autobidJobIds = $mailJobRepository->massInsertToLocal($jobs);

            foreach ($autobidJobIds as $autobidJobId) {
                AuctionWinnerMailExecutionJob::dispatch($autobidJobId)
                    ->onQueue('mailer');
            }
        } catch (Exception) {
            $logger->error('[AuctionWinnerMailPreparationJob] Failed to prepare auction winner mail', [
                'bid_id' => $this->bidId,
            ]);
        }
    }
}
