<?php

namespace App\Common\Exceptions\Bid;

use Exception;

class LessBidPlacedException extends Exception
{
    /**
     * @param int $amount
     */
    public function __construct(
        public int $amount,
    )
    {
        parent::__construct();
    }
}
