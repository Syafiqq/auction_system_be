<?php

namespace App\Domain\Entity\Enum;

enum BidStatusEnum: string
{
    case inProgressLeading = 'IN PROGRESS (LEADING)';
    case inProgress = 'IN PROGRESS';
    case lose = 'LOSE';
    case win = 'WIN';
}
