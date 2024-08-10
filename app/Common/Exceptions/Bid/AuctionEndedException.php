<?php

namespace App\Common\Exceptions\Bid;

use Exception;
use Illuminate\Support\Carbon;

class AuctionEndedException extends Exception
{
    /**
     * @param Carbon $date
     */
    public function __construct(
        public Carbon $date,
    )
    {
        parent::__construct();
    }
}
