<?php

namespace App\Domain\Entity\Helper;

use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Enum\BidStatusEnum;
use App\Domain\Entity\User;

class AuctionItemHelper
{
    public static function getAuctionStatus(User $user, AuctionItem $auctionItem): BidStatusEnum
    {
        if ($auctionItem->has_winner) {
            if ($auctionItem->current_price?->user_id === $user->id) {
                return BidStatusEnum::win;
            }
            return BidStatusEnum::lose;
        }
        if ($auctionItem->current_price?->user_id === $user->id) {
            return BidStatusEnum::inProgressLeading;
        }
        return BidStatusEnum::inProgress;
    }
}
