<?php

namespace App\Domain\Entity\Enum;

enum BillStatusEnum: string
{
    case paid = 'PAID';
    case unpaid = 'UNPAID';
    case forfeited = 'FORFEITED';
}
