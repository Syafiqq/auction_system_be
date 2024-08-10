<?php

namespace App\Common\Exceptions\Bid;

use Exception;

class AuctionEndedEarlyException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
