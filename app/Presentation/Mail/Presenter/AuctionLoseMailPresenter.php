<?php

namespace App\Presentation\Mail\Presenter;

class AuctionLoseMailPresenter
{
    private string $productName;
    private string $bidAmount;
    private string $diff;
    private string $bidUrl;

    /**
     * @param string $productName
     * @param string $bidAmount
     * @param string $diff
     * @param string $bidUrl
     */
    public function __construct(
        string $productName,
        string $bidAmount,
        string $diff,
        string $bidUrl
    )
    {
        $this->productName = $productName;
        $this->bidAmount = $bidAmount;
        $this->diff = $diff;
        $this->bidUrl = $bidUrl;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getBidAmount(): string
    {
        return $this->bidAmount;
    }

    public function getDiff(): string
    {
        return $this->diff;
    }

    public function getBidUrl(): string
    {
        return $this->bidUrl;
    }
}
