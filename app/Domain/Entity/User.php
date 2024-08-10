<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Enum\UserTypeEnum;
use App\Domain\Entity\Trait\AuthenticatableUser;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Override;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property UserTypeEnum $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $autobid_capacity
 * @property int|null $autobid_percentage_warning
 * @method static where(string $string, string $username)
 * @method static paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null, $total = null)
 * @property-read Collection<int, Bid> $bids
 * @property-read int|null $bids_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read UserAuctionAutobid|null $autobid
 * @mixin Eloquent
 */
class User extends Model implements Authenticatable
{
    use HasApiTokens, AuthenticatableUser, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function autobids(): HasMany
    {
        return $this->hasMany(UserAuctionAutobid::class);
    }

    public function auctions(): HasManyThrough
    {
        return $this->hasManyThrough(
            AuctionItem::class,
            Bid::class,
            'user_id',
            'id',
            'id',
            'auction_item_id'
        );
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
            'password' => 'hashed',
            'type' => UserTypeEnum::class,
        ];
    }
}
