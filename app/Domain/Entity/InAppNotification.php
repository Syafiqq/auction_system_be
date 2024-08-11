<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Enum\NotificationType;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @property int $id
 * @property string $title
 * @property string $body
 * @property NotificationType $type
 * @property int $type_version
 * @property array $raw_data
 * @property int $user_id
 * @mixin Eloquent
 */
class InAppNotification extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'body',
        'type',
        'type_version',
        'raw_data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'raw_data' => 'array',
            'type' => NotificationType::class,
        ];
    }
}
