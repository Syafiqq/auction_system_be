<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\InAppNotificationLocalDataSource;
use App\Domain\Entity\Dto\InAppNotificationCreateRequestDto;
use App\Domain\Entity\InAppNotification;
use Illuminate\Pagination\AbstractPaginator;
use Override;

class InAppNotificationLocalDataSourceImpl implements InAppNotificationLocalDataSource
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function findPaginated(
        int $userId,
        int $page,
        int $itemPerPage
    ): AbstractPaginator
    {
        return InAppNotification::query()->where('user_id', $userId)->orderByDesc('created_at')->paginate($itemPerPage, page: $page);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function insert(InAppNotificationCreateRequestDto $data): void
    {
        $builder = new InAppNotification();
        $builder->title = $data->title;
        $builder->body = $data->body;
        $builder->type = $data->type;
        $builder->type_version = $data->typeVersion;
        $builder->raw_data = $data->rawData;
        $builder->user_id = $data->userId;
        $builder->save();
    }
}
