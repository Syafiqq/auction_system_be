<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Enum\BillStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property int $auction_item_id
 * @property int $user_id
 * @property int $bid_id
 * @property Carbon $issued_at
 * @property Carbon $due_at
 * @property Carbon $paid_at
 * @property BillStatusEnum $status
 * @property-read AuctionItem|null $auctionItem
 * @property-read User|null $user
 * @property-read Bid|null $bid
 */
class Bill extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bid_id',
        'auction_item_id',
        'no',
        'issued_at',
        'due_at',
        'paid_at',
        'status',
    ];

    public function auctionItem(): BelongsTo
    {
        return $this->belongsTo(AuctionItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
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
            'status' => BillStatusEnum::class,
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }
}
