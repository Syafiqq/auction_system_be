<?php

namespace App\Data\DataSource\Local\Concrete;

use App\Data\DataSource\Local\Abstract\AutobidJobLocalDataSource;
use App\Domain\Entity\AutobidJob;
use Illuminate\Support\Facades\DB;
use Override;
use Throwable;

class AutobidJobLocalDataSourceImpl implements AutobidJobLocalDataSource
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
        // Insert data
        try {
            DB::transaction(function () use ($data) {
                foreach ($data as $object) {
                    $object->save();
                }
            });
        } catch (Throwable) {
        }

        // Retrieve and return the IDs of the inserted records
        return AutobidJob::query()
            ->latest('id')
            ->take(count($data))
            ->pluck('id')
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function find(int $at): ?AutobidJob
    {
        return AutobidJob::where('id', $at)->first();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function invalidateAllPreviousJob(int $at): void
    {
        AutobidJob::where('auction_item_id', $at)->delete();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateJobStatus(int $at, bool $processed): void
    {
        $job = AutobidJob::where('id', $at)->first();
        if ($job) {
            $job->processed = $processed;
            $job->save();
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function countProcessedSession(int $sessionId): array
    {
        $processed = AutobidJob::where('session_id', $sessionId)
            ->pluck('processed')
            ->toArray();
        return [
            'processed' => count(array_filter($processed, fn($value) => $value === true)),
            'unprocessed' => count(array_filter($processed, fn($value) => $value !== true)),
        ];
    }
}
