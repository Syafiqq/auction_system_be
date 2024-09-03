<?php

namespace App\Presentation\Jobs;

use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\AuctionWinnerMailJobRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\Repository\BillRepository;
use App\Domain\Repository\UserRepository;
use App\Presentation\Mail\AuctionLoseMailer;
use App\Presentation\Mail\AuctionWinnerMailer;
use App\Presentation\Mail\Presenter\AuctionLoseMailPresenter;
use App\Presentation\Mail\Presenter\AuctionWinnerMailPresenter;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\MailManager;
use NumberFormatter;
use Psr\Log\LoggerInterface;

class AuctionWinnerMailExecutionJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param int $auctionWinnerId
     */
    public function __construct(
        protected int $auctionWinnerId,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(
        BidRepository                  $bidRepository,
        AuctionItemRepository          $auctionItemRepository,
        AuctionWinnerMailJobRepository $mailJobRepository,
        UserRepository                 $userRepository,
        BillRepository                 $billRepository,
        MailManager                    $mailProvider,
        LoggerInterface                $logger,
    ): void
    {
        try {
            $job = $mailJobRepository->findFromLocal($this->auctionWinnerId);

            $user = $userRepository->findFromLocal($job->user_id);
            $auction = $auctionItemRepository->findFromLocal($job->auction_item_id);
            $bid = $bidRepository->findLatestBidFromLocal($job->user_id, $job->auction_item_id);
            $bill = $billRepository->findBillFromLocal($job->user_id, $job->auction_item_id, $bid->id);

            if ($bid == null) {
                return;
            }

            $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
            if ($bill != null && $auction->winner_id == $bill->bid_id) {
                $productId = $auction->id;
                $presenter = new AuctionWinnerMailPresenter(
                    productName: $auction->name,
                    bidAmount: $numberFormatter->formatCurrency($bid->amount, 'USD'),
                    productId: $auction->idPadded(),
                    bidAt: $bid->bid_at->format('M d, Y H:i:s'),
                    billDueAt: $bill->due_at->format('M d, Y H:i:s'),
                    bidUrl: config('frontend.frontend_url') . "/auction/{$productId}/place-bid",
                    paymentUrl: config('frontend.frontend_url') . "/auction/{$productId}/bill",
                );

                $mailProvider->to($user->email, $user->username)
                    ->queue(new AuctionWinnerMailer($presenter));
            } else {
                $winnerBid = $bidRepository->findFromLocal($auction->winner_id);
                $productId = $auction->id;
                $diff = $winnerBid->amount - $bid->amount;
                $presenter = new AuctionLoseMailPresenter(
                    productName: $auction->name,
                    bidAmount: $numberFormatter->formatCurrency($bid->amount, 'USD'),
                    diff: $numberFormatter->formatCurrency($diff, 'USD'),
                    bidUrl: config('frontend.frontend_url') . "/auction/{$productId}/place-bid",
                );

                $mailProvider->to($user->email, $user->username)
                    ->queue(new AuctionLoseMailer($presenter));
            }
        } catch (Exception) {
            $logger->error('[AuctionWinnerMailExecutionJob] Failed to execute auction winner mail', [
                'job_id' => $this->auctionWinnerId,
            ]);
        } finally {
            $mailJobRepository->deleteToLocal($this->auctionWinnerId);
        }
    }
}
