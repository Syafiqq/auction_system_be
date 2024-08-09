<?php

namespace App\Domain\Entity;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $auction_item_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AuctionItem $auctionItem
 * @property-read string|null $url
 * @mixin Eloquent
 */
class AuctionItemImage extends Model
{
    use HasFactory, Notifiable;

    static string $publicStoragePath = 'images/auction_items';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auction_item_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
    ];

    public function auctionItem(): BelongsTo
    {
        return $this->belongsTo(AuctionItem::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (empty($this->name)) {
            return null;
        }
        return Storage::url(self::$publicStoragePath . '/' . $this->name);
    }
}
