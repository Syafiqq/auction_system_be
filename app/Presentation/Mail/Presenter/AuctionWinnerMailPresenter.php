<?php

namespace App\Presentation\Mail\Presenter;

class AuctionWinnerMailPresenter
{
    private string $productName;
    private string $bidAmount;
    private string $productId;
    private string $bidAt;
    private string $billDueAt;
    private string $bidUrl;
    private string $paymentUrl;

    /**
     * @param string $productName
     * @param string $bidAmount
     * @param string $productId
     * @param string $bidAt
     * @param string $billDueAt
     * @param string $bidUrl
     * @param string $paymentUrl
     */
    public function __construct(
        string $productName,
        string $bidAmount,
        string $productId,
        string $bidAt,
        string $billDueAt,
        string $bidUrl,
        string $paymentUrl
    )
    {
        $this->productName = $productName;
        $this->bidAmount = $bidAmount;
        $this->productId = $productId;
        $this->bidAt = $bidAt;
        $this->billDueAt = $billDueAt;
        $this->bidUrl = $bidUrl;
        $this->paymentUrl = $paymentUrl;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getBidAmount(): string
    {
        return $this->bidAmount;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getBidAt(): string
    {
        return $this->bidAt;
    }

    public function getBillDueAt(): string
    {
        return $this->billDueAt;
    }

    public function getBidUrl(): string
    {
        return $this->bidUrl;
    }

    public function getPaymentUrl(): string
    {
        return $this->paymentUrl;
    }
}
