<?php

namespace App\Domain\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $starting_price
 * @property Carbon $end_time
 * @property bool $has_winner
 * @property int|null $winner_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, AuctionItemImage> $images
 * @property-read int|null $images_count
 * @property-read Collection<int, Bid> $bids
 * @property-read int|null $bids_count
 * @property-read Bid|null $current_price
 * @property-read Bid|null $winner
 * @property-read UserAuctionAutobid|null $autobid
 * @property-read Bill|null $bill
 * @method static paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null, $total = null)
 * @mixin Eloquent
 */
class AuctionItem extends Model
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auction_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'starting_price',
        'end_time',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'current_price',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(AuctionItemImage::class);
    }

    public function winner(): HasOne
    {
        return $this->hasOne(Bid::class);
    }

    public function autobids(): HasMany
    {
        return $this->hasMany(UserAuctionAutobid::class);
    }

    public function getCurrentPriceAttribute(): ?Bid
    {
        try {
            /** @var Bid $bid */
            $bid = $this->bids()
                ->orderByDesc('amount')
                ->orderByDesc('bid_at')
                ->firstOrFail();
            return $bid;
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function bill(): HasOne
    {
        return $this->hasOne(Bill::class);
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
            'end_time' => 'datetime',
        ];
    }
}
