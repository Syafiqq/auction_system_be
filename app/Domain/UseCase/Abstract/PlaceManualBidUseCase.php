<?php

namespace App\Domain\UseCase\Abstract;

use App\Common\Exceptions\Bid\AuctionEndedEarlyException;
use App\Common\Exceptions\Bid\AuctionEndedException;
use App\Common\Exceptions\Bid\LessBidPlacedException;
use App\Common\Exceptions\Bid\NewerBidPresentException;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Dto\BidRequestDto;

interface PlaceManualBidUseCase
{
    /**
     * @param BidRequestDto $data
     * @return Bid
     * @throws NewerBidPresentException
     * @throws LessBidPlacedException
     * @throws AuctionEndedException
     * @throws AuctionEndedEarlyException
     */
    public function execute(BidRequestDto $data): Bid;
}
