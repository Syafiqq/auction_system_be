<?php

namespace App\Domain\UseCase\Concrete;

use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;
use App\Domain\Entity\Enum\BidTypeEnum;
use App\Domain\Repository\AuctionItemRepository;
use App\Domain\Repository\BidRepository;
use App\Domain\UseCase\Abstract\PlaceManualBidUseCase;
use App\Domain\UseCase\Trait\PlaceBidPreparation;
use App\Presentation\Events\BidPlacedDetailEvent;
use App\Presentation\Events\BidPlacedGlobalEvent;
use App\Presentation\Jobs\AutobidPreparationJob;
use App\Presentation\Jobs\BidPlacedMailBroadcastJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Override;

class PlaceManualBidUseCaseImpl implements PlaceManualBidUseCase
{
    use PlaceBidPreparation;

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
    public function execute(BidRequestDto $data): Bid
    {
        $auction = $this->auctionItemRepository->findFromLocal($data->auctionItem);
        /** @var ?Bid $bid */
        $bid = null;
        try {
            $bid = $this->bidRepository->findNewestFromLocal($data->auctionItem);
        } catch (ModelNotFoundException) {
        }

        $this->validate($auction, $bid, $data);

        $result = $this->bidRepository->insertToLocal($data, BidTypeEnum::manual);

        AutobidPreparationJob::dispatch($bid->id ?? 1, $auction->id, $data->user->id)
            ->onQueue('autobid_preparation');
        BidPlacedMailBroadcastJob::dispatch($result->id)
            ->onQueue('mailer');
        BidPlacedGlobalEvent::dispatch($result);
        BidPlacedDetailEvent::dispatch($result);

        return $result;
    }
}
