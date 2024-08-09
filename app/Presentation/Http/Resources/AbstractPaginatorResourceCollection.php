<?php

namespace App\Presentation\Http\Resources;

use App\Presentation\Http\Trait\ResourceCreation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AbstractPaginatorResourceCollection extends ResourceCollection
{
    use ResourceCreation;

    private Closure $morph;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->morph->call($this, $this->collection),
        ];
    }

    public function withMorph(Closure $morph): self
    {
        $this->morph = $morph;
        return $this;
    }
}
