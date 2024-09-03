<?php

namespace App\Presentation\Mail\Presenter;

use NumberFormatter;

class AutoBidExceedMailPresenter
{
    private string $productName;
    private string $autoBidBalance;
    private string $bidAmount;
    private string $bidUrl;
    private string $autoBidConfigurationUrl;

    /**
     * @param string $productName
     * @param int $autoBidBalance
     * @param int $bidAmount
     * @param string $bidUrl
     * @param string $autoBidConfigurationUrl
     */
    public function __construct(
        string $productName,
        int    $autoBidBalance,
        int    $bidAmount,
        string $bidUrl,
        string $autoBidConfigurationUrl
    )
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

        $this->productName = $productName;
        $this->autoBidBalance = $numberFormatter->formatCurrency($autoBidBalance, 'USD');;
        $this->bidAmount = $numberFormatter->formatCurrency($bidAmount, 'USD');;
        $this->bidUrl = $bidUrl;
        $this->autoBidConfigurationUrl = $autoBidConfigurationUrl;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getAutoBidBalance(): string
    {
        return $this->autoBidBalance;
    }

    public function getBidAmount(): string
    {
        return $this->bidAmount;
    }

    public function getBidUrl(): string
    {
        return $this->bidUrl;
    }

    public function getAutoBidConfigurationUrl(): string
    {
        return $this->autoBidConfigurationUrl;
    }
}
