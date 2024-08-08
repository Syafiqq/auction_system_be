<?php

namespace App\Presentation\Http\Trait;

trait ResourceCreation
{
    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     * @return self
     */
    static function new(mixed $resource): self
    {
        return new self($resource);
    }
}
