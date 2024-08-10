<?php

namespace App\Domain\UseCase\Trait;

use App\Common\Exceptions\Bid\AuctionEndedException;
use App\Common\Exceptions\Bid\LessBidPlacedException;
use App\Common\Exceptions\Bid\NewerBidPresentException;
use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;

trait PlaceBidPreparation
{
    /**
     * @param AuctionItem $auction
     * @param Bid|null $highestBid
     * @param BidRequestDto $data
     * @return void
     * @throws AuctionEndedException
     * @throws LessBidPlacedException
     * @throws NewerBidPresentException
     */
    public function validate(AuctionItem $auction, ?Bid $highestBid, BidRequestDto $data): void
    {
        if ($highestBid != null) {
            if ($data->lastBidReference != null && $highestBid->id != $data->lastBidReference) {
                throw new NewerBidPresentException($highestBid);
            } else if ($highestBid->amount >= $data->amount) {
                throw new LessBidPlacedException($highestBid->amount);
            }
        } else {
            if ($auction->starting_price >= $data->amount) {
                throw new LessBidPlacedException($auction->starting_price);
            }
        }
        if ($auction->end_time < now()) {
            throw new AuctionEndedException($auction->end_time);
        }
    }
}
