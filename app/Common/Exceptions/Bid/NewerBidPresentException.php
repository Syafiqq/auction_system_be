<?php

namespace App\Common\Exceptions\Bid;

use App\Domain\Entity\Bid;
use Exception;

class NewerBidPresentException extends Exception
{
    /**
     * @param Bid $bid
     */
    public function __construct(
        public Bid $bid,
    )
    {
        parent::__construct();
    }
}
