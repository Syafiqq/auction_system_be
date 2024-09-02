<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\AuctionWinnerMailJobLocalDataSource;
use App\Domain\Entity\AuctionWinnerMailJob;
use Illuminate\Support\Facades\DB;
use Override;
use Throwable;

class AuctionWinnerMailJobLocalDataSourceImpl implements AuctionWinnerMailJobLocalDataSource
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function massInsert(array $data): array
    {
        try {
            DB::transaction(function () use ($data) {
                foreach ($data as $object) {
                    $object->save();
                }
            });
        } catch (Throwable) {
        }

        return AuctionWinnerMailJob::query()
            ->latest('id')
            ->take(count($data))
            ->pluck('id')
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function find(int $at): ?AuctionWinnerMailJob
    {
        return AuctionWinnerMailJob::query()->where('id', $at)->first();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function delete(int $at): void
    {
        AuctionWinnerMailJob::query()->where('id', $at)->delete();
    }
}
