<?php

namespace App\Data\Repository;

use App\Data\DataSource\Local\Abstract\InAppNotificationLocalDataSource;
use App\Domain\Entity\Dto\InAppNotificationCreateRequestDto;
use App\Domain\Repository\InAppNotificationRepository;
use Illuminate\Pagination\AbstractPaginator;
use Override;

class InAppNotificationRepositoryImpl implements InAppNotificationRepository
{
    public function __construct(
        protected InAppNotificationLocalDataSource $localDataSource,
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findPaginatedFromLocal(
        int $userId,
        int $page,
        int $itemPerPage
    ): AbstractPaginator
    {
        return $this->localDataSource->findPaginated($userId, $page, $itemPerPage);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insertToLocal(InAppNotificationCreateRequestDto $data): void
    {
        $this->localDataSource->insert($data);
    }
}
