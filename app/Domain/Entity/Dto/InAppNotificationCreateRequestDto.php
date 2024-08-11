<?php

namespace App\Domain\Entity\Dto;

use App\Domain\Entity\Enum\NotificationType;

class InAppNotificationCreateRequestDto
{
    /**
     * @param string $title
     * @param string $body
     * @param NotificationType $type
     * @param int $typeVersion
     * @param array $rawData
     * @param int $userId
     */
    public function __construct(
        public string           $title,
        public string           $body,
        public NotificationType $type,
        public int              $typeVersion,
        public array            $rawData,
        public int              $userId,
    )
    {
    }
}
