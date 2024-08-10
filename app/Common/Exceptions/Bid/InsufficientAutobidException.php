<?php

namespace App\Common\Exceptions\Bid;

use Exception;

class InsufficientAutobidException extends Exception
{
    /**
     * @param int $balance
     * @param int $required
     */
    public function __construct(
        public int $balance,
        public int $required,
    )
    {
        parent::__construct();
    }
}
