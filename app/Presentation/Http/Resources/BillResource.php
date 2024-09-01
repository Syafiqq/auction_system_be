<?php

namespace App\Presentation\Http\Resources;

use App\Domain\Entity\Bill;
use App\Presentation\Http\Trait\ResourceCreation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    use ResourceCreation;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public string $collects = Bill::class;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'no' => $this->no,
            'issued_at' => $this->issued_at,
            'due_at' => $this->due_at,
            'paid_at' => $this->paid_at,
            'status' => $this->status
        ];
    }
}
