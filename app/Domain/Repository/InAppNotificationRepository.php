<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Dto\InAppNotificationCreateRequestDto;
use App\Domain\Entity\InAppNotification;
use Illuminate\Pagination\AbstractPaginator;

interface InAppNotificationRepository
{
    /**
     * @param int $userId
     * @param int $page
     * @param int $itemPerPage
     * @return AbstractPaginator<InAppNotification>
     */
    public function findPaginatedFromLocal(
        int $userId,
        int $page,
        int $itemPerPage
    ): AbstractPaginator;

    /**
     * @param InAppNotificationCreateRequestDto $data
     * @return void
     */
    public function insertToLocal(InAppNotificationCreateRequestDto $data): void;
}
