<?php

namespace App\Domain\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $session_id
 * @property int $auction_item_id
 * @property int $user_id
 * @property bool|null $processed
 * @mixin Eloquent
 */
class AutobidJob extends Model
{
    use SoftDeletes;

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
        'session_id',
        'auction_item_id',
        'user_id',
    ];
}
