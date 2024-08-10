<?php

namespace App\Domain\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $starting_price
 * @property Carbon $end_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, AuctionItemImage> $images
 * @property-read int|null $images_count
 * @property-read Collection<int, Bid> $bids
 * @property-read int|null $bids_count
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

    public function images(): HasMany
    {
        return $this->hasMany(AuctionItemImage::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
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
