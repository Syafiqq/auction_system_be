<?php

namespace App\Presentation\Http\Resources;

use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Helper\AuctionItemHelper;
use App\Presentation\Http\Trait\ResourceCreation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionItemOwnedResource extends JsonResource
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
            'status' => AuctionItemHelper::getAuctionStatus($request->user(), $this->resource)->value,
        ];
    }
}
