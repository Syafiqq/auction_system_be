<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Enum\BidTypeEnum;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property BidTypeEnum $type
 * @property int $amount
 * @property Carbon $bid_at
 * @property int $user_id
 * @property int $auction_item_id
 * @property-read AuctionItem|null $auctionItem
 * @property-read User|null $user
 * @mixin Eloquent
 */
class Bid extends Model
{
    use HasFactory, Notifiable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bids';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'bid_at',
        'user_id',
        'auction_item_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auctionItem(): BelongsTo
    {
        return $this->belongsTo(AuctionItem::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'type' => BidTypeEnum::class,
            'bid_at' => 'datetime',
        ];
    }
}
