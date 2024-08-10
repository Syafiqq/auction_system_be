<?php

namespace App\Domain\Entity\Enum;

enum BidTypeEnum: string
{
    case manual = 'manual';
    case auto = 'auto';
}
