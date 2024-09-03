<?php

namespace App\Presentation\Jobs;

use App\Domain\Entity\AutobidJob;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\AutobidJobRepository;
use App\Domain\Repository\BidRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Queue\Queueable;

class PostAutobidPreparationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param int $sessionId
     * @param int $auctionItemId
     */
    public function __construct(
        protected int $sessionId,
        protected int $auctionItemId,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        AutobidJobRepository  $autobidJobRepository,
        AuctionItemRepository $auctionItemRepository,
        BidRepository         $bidRepository,
    ): void
    {
        $processed = $autobidJobRepository->countProcessedSessionFromLocal($this->sessionId);
        if (($processed['processed'] == 0)) {
            return;
        }

        try {
            $highestBid = $bidRepository->findNewestFromLocal($this->auctionItemId);
        } catch (ModelNotFoundException) {
            $highestBid = null;
        }

        $sessionId = $highestBid->id ?? ($this->sessionId + 1);

        $autobidJobRepository->invalidateAllPreviousJobFromLocal($this->auctionItemId);

        $userIds = $auctionItemRepository->findAllAutobidUserFromLocal($this->auctionItemId, $highestBid->user_id);
        $autobidJobs = array_map(
            fn($userId) => new AutobidJob([
                'session_id' => $sessionId,
                'auction_item_id' => $this->auctionItemId,
                'user_id' => $userId,
            ]),
            $userIds
        );

        if (empty($autobidJobs)) {
            return;
        }

        $autobidJobIds = $autobidJobRepository->massInsertToLocal($autobidJobs);

        foreach ($autobidJobIds as $autobidJobId) {
            AutobidExecutionJob::dispatch($autobidJobId)
                ->onQueue('autobid_execution');
        }
        PostAutobidPreparationJob::dispatch($sessionId, $this->auctionItemId)
            ->onQueue('autobid_execution');
    }
}
