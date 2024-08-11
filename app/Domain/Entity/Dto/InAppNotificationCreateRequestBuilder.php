<?php

namespace App\Domain\Entity\Dto;

use App\Domain\Entity\Enum\NotificationType;

trait InAppNotificationCreateRequestBuilder
{
    public function builder(
        int   $version,
        array $data,
    ): InAppNotificationCreateRequestDto
    {
        return match ($this) {
            NotificationType::insufficientAutoBidBalance => $this->insufficientAutoBidBalance($version, $data),
            NotificationType::bidWinner => $this->bidWinner($version, $data),
            NotificationType::autobidUsageWarning => $this->autobidUsageWarning($version, $data),
            NotificationType::autobidPlaced => $this->autobidPlaced($version, $data),
        };

    }

    public function insufficientAutoBidBalance(
        int   $version,
        array $data,
    ): InAppNotificationCreateRequestDto
    {
        return new InAppNotificationCreateRequestDto(
            $this->getTitle($version),
            sprintf(
                $this->getDescription($version),
                $data['balance'],
                $data['price']
            ),
            $this,
            $version,
            [
                'user' => $data['for'],
                'auction' => $data['at'],
            ],
            $data['for'],
        );
    }

    public function bidWinner(
        int   $version,
        array $data,
    ): InAppNotificationCreateRequestDto
    {
        return new InAppNotificationCreateRequestDto(
            $this->getTitle($version),
            sprintf(
                $this->getDescription($version),
                $data['name'],
                $data['price'],
            ),
            $this,
            $version,
            [
                'user' => $data['for'],
                'auction' => $data['at'],
                'price' => $data['price']
            ],
            $data['for'],
        );
    }

    public function autobidUsageWarning(
        int   $version,
        array $data,
    ): InAppNotificationCreateRequestDto
    {
        return new InAppNotificationCreateRequestDto(
            $this->getTitle($version),
            sprintf(
                $this->getDescription($version),
                $data['usage'],
            ),
            $this,
            $version,
            [
                'user' => $data['for'],
                'auction' => $data['at'],
            ],
            $data['for'],
        );
    }

    public function autobidPlaced(
        int   $version,
        array $data,
    ): InAppNotificationCreateRequestDto
    {
        return new InAppNotificationCreateRequestDto(
            $this->getTitle($version),
            sprintf(
                $this->getDescription($version),
                $data['name'],
                $data['price'],
            ),
            $this,
            $version,
            [
                'user' => $data['for'],
                'auction' => $data['at'],
            ],
            $data['for'],
        );
    }
}
