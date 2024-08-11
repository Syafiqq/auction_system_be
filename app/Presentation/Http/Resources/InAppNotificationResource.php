<?php

namespace App\Presentation\Http\Resources;

use App\Domain\Entity\InAppNotification;
use App\Presentation\Http\Trait\ResourceCreation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InAppNotificationResource extends JsonResource
{
    use ResourceCreation;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public string $collects = InAppNotification::class;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'type_version' => $this->type_version,
            'created_at' => $this->created_at,
            'raw_data' => json_encode($this->raw_data),
        ];
    }
}
