<?php

namespace Database\Factories\Domain\Entity;

use App\Domain\Entity\AuctionItemImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionItemImageFactory extends Factory
{
    protected $model = AuctionItemImage::class;

    public function definition(): array
    {
        $word = fake()->word();
        return [
            'name' => "https://placehold.co/400x400?text={$word}",
        ];
    }
}
