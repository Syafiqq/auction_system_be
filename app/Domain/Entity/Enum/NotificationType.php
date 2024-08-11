<?php

namespace App\Domain\Entity\Enum;

use App\Domain\Entity\Dto\InAppNotificationCreateRequestBuilder;

enum NotificationType: string
{
    use InAppNotificationCreateRequestBuilder;

    case insufficientAutoBidBalance = 'insufficient_auto_bid_balance';
    case bidWinner = 'bid_winner';
    case autobidUsageWarning = 'autobid_usage_warning';
    case autobidPlaced = 'auto_bid_placed';

    public function getTitle(int $version): string
    {
        return match ($this) {
            self::insufficientAutoBidBalance => 'Insufficient Auto Bid Balance',
            self::bidWinner => 'Bid Winner',
            self::autobidUsageWarning => 'Auto Bid Usage Warning',
            self::autobidPlaced => 'Auto Bid Placed',
        };
    }

    public function getDescription(int $version): string
    {
        return match ($this) {
            self::insufficientAutoBidBalance => 'You have insufficient balance to place an autobid, current balance `$%d`, required price `$%d`',
            self::bidWinner => 'Congratulations! You have won the auction of `%s` at the price of `$%d`',
            self::autobidUsageWarning => 'You have used `%.2f%%` of your auto bid balance',
            self::autobidPlaced => 'Auto bid placed for `%s` at the price of `$%d`',
        };
    }
}
