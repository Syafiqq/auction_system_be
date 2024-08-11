<?php

namespace App\Data\DataSource\Local\Abstract;


use App\Domain\Entity\Dto\InAppNotificationCreateRequestDto;
use App\Domain\Entity\InAppNotification;
use Illuminate\Pagination\AbstractPaginator;

interface InAppNotificationLocalDataSource
{
    /**
     * @param int $userId
     * @param int $page
     * @param int $itemPerPage
     * @return AbstractPaginator<InAppNotification>
     */
    public function findPaginated(
        int $userId,
        int $page,
        int $itemPerPage
    ): AbstractPaginator;

    /**
     * @param InAppNotificationCreateRequestDto $data
     * @return void
     */
    public function insert(InAppNotificationCreateRequestDto $data): void;
}
