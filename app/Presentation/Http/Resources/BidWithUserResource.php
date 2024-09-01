<?php

namespace App\Presentation\Http\Resources;

use App\Domain\Entity\Bid;
use App\Presentation\Http\Trait\ResourceCreation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidWithUserResource extends JsonResource
{
    use ResourceCreation;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public string $collects = Bid::class;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'auction_id' => $this->auction_item_id,
            'amount' => $this->amount,
            'bid_at' => $this->bid_at,
            'user' => UserLimitedResource::new($this->user),
        ];
    }
}
