<?php

namespace App\Presentation\Http\Resources;

use App\Domain\Entity\AuctionItem;
use App\Presentation\Http\Trait\ResourceCreation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionItemWinnerResource extends JsonResource
{
    use ResourceCreation;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public string $collects = AuctionItem::class;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'starting_price' => $this->starting_price,
            'end_time' => $this->end_time,
            'images' => AuctionItemImageResource::collection($this->images),
            'current_price' => BidResource::new($this->current_price),
            'payment_status' => $this->bill->status,
        ];
    }
}
