<?php

namespace App\Presentation\Jobs;

use App\Common\Exceptions\Bid\AuctionEndedEarlyException;
use App\Common\Exceptions\Bid\AuctionEndedException;
use App\Common\Exceptions\Bid\InsufficientAutobidException;
use App\Common\Exceptions\Bid\LessBidPlacedException;
use App\Common\Exceptions\Bid\NewerBidPresentException;
use App\Domain\Repository\AutobidJobRepository;
use App\Domain\UseCase\Abstract\PlaceAutoBidUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AutobidExecutionJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 2;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff = 1;

    /**
     * Create a new job instance.
     *
     * @param int $jobId
     */
    public function __construct(
        protected int $jobId,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        AutobidJobRepository $autobidJobRepository,
        PlaceAutoBidUseCase  $autoBidUseCase,
    ): void
    {
        $job = $autobidJobRepository->findFromLocal($this->jobId);
        if($job === null) {
            return;
        }
        try {
            $autoBidUseCase->execute($job->user_id, $job->auction_item_id);
            $autobidJobRepository->updateJobStatusFromLocal($this->jobId, true);
        } catch (AuctionEndedEarlyException|AuctionEndedException|InsufficientAutobidException) {
            $autobidJobRepository->updateJobStatusFromLocal($this->jobId, false);
        } catch (LessBidPlacedException|NewerBidPresentException) {
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff);
            }
        }
    }
}
