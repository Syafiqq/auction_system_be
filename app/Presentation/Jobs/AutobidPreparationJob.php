<?php

namespace App\Presentation\Jobs;

use App\Domain\Entity\AutobidJob;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\AutobidJobRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AutobidPreparationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param int $sessionId
     * @param int $auctionItemId
     * @param int|null $exceptUserId
     */
    public function __construct(
        protected int  $sessionId,
        protected int  $auctionItemId,
        protected ?int $exceptUserId,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        AutobidJobRepository  $autobidJobRepository,
        AuctionItemRepository $auctionItemRepository
    ): void
    {
        $autobidJobRepository->invalidateAllPreviousJobFromLocal($this->auctionItemId);

        $userIds = $auctionItemRepository->findAllAutobidUserFromLocal($this->auctionItemId, $this->exceptUserId);
        $autobidJobs = array_map(
            fn($userId) => new AutobidJob([
                'session_id' => $this->sessionId,
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
        PostAutobidPreparationJob::dispatch($this->sessionId, $this->auctionItemId)
            ->onQueue('autobid_execution');
    }
}
