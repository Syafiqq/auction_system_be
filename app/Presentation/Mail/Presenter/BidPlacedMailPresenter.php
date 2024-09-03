<?php

namespace App\Presentation\Mail\Presenter;

use App\Domain\Entity\AuctionItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use NumberFormatter;

class BidPlacedMailPresenter
{

    // add private property with public getter
    private string $bidAmountString = '-';
    private ?string $diffAmountString = null;

    public function __construct(
        public AuctionItem $auctionItem,
        public Collection  $bids
    )
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

        if ($this->bids->count() > 0) {
            $this->bidAmountString = $numberFormatter->formatCurrency($this->bids->first()->amount, 'USD');
        }
        if ($this->bids->count() > 1) {
            $diff = $this->bids->first()->amount - $this->bids->last()->amount;
            $this->diffAmountString = $numberFormatter->formatCurrency($diff, 'USD');
        }
    }

    public function bidAmount(): string
    {
        return $this->bidAmountString;
    }

    public function diffAmount(): ?string
    {
        return $this->diffAmountString;
    }

    public function productId(): string
    {
        return $this->auctionItem->id;
    }

    public function productIdDisplay(): string
    {
        return $this->auctionItem->idPadded();
    }

    public function productName(): string
    {
        return $this->auctionItem->name;
    }

    public function bidAt(): ?Carbon
    {
        return $this->bids->first()->bid_at;
    }

    public function bidAtDisplay(): string
    {
        $bidAt = $this->bidAt();
        return $bidAt ? $bidAt->format('M d, Y H:i:s') : '-';
    }

    public function bidUrl(): string
    {
        $productId = $this->productId();
        return config('frontend.frontend_url') . "/auction/{$productId}/place-bid";
    }
}
