<?php

namespace App\Domain\UseCase\Abstract;

use App\Common\Exceptions\Bid\AuctionEndedEarlyException;
use App\Common\Exceptions\Bid\AuctionEndedException;
use App\Common\Exceptions\Bid\InsufficientAutobidException;
use App\Common\Exceptions\Bid\LessBidPlacedException;
use App\Common\Exceptions\Bid\NewerBidPresentException;

interface PlaceAutoBidUseCase
{
    /**
     * @param int $for
     * @param int $at
     * @return void
     * @throws AuctionEndedException
     * @throws LessBidPlacedException
     * @throws NewerBidPresentException
     * @throws AuctionEndedEarlyException
     * @throws InsufficientAutobidException
     */
    public function execute(int $for, int $at): void;
}
