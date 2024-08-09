<?php

namespace Database\Factories\Domain\Entity;

use App\Domain\Entity\AuctionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuctionItemFactory extends Factory
{
    static array $goods = [
        'Apple iPhone X',
        'Samsung Galaxy S10',
        'Google Pixel 3a',
        'Huawei Mate 20 Pro',
        'OnePlus 7 Pro',
        'LG V50 ThinQ',
        'Sony Xperia XZ3',
        'Nokia 9 PureView',
        'HTC U11+',
        'Motorola Moto Z3 Play',
        'BlackBerry KEYone',
        'Google Pixel 2 XL',
        'Samsung Galaxy S9+',
        'LG G7 ThinQ',
        'OnePlus 5T',
        'Huawei P20 Pro',
        'Sony Xperia XZ2 Compact',
        'Nokia 8 Sirocco',
        'HTC U Ultra',
        'Google Pixel 3',
        'Samsung Galaxy S10e',
        'LG V40 ThinQ',
        'OnePlus 6T',
        'Huawei Mate 20',
        'Sony Xperia XZ3 Compact',
        'Nokia 7 Plus',
        'HTC U12+',
        'Google Pixel 3a XL',
        'Samsung Galaxy S9',
        'LG G7 ThinQ+',
        'OnePlus 6',
        'Huawei P20 Lite',
        'Sony Xperia XZ2 Premium',
        'Nokia 8 Sirocco',
        'HTC U11 Life',
        'Google Pixel 2',
        'Samsung Galaxy S8+',
        'LG V35 ThinQ',
        'OnePlus 5',
        'Huawei Mate 10 Pro',
        'Sony Xperia XZ1 Compact',
        'Nokia 6.1 Plus',
        'HTC U Play',
        'Google Pixel XL',
        'Samsung Galaxy S7 edge',
        'LG G5',
        'OnePlus 3T',
        'Huawei P9',
        'Sony Xperia X Performance',
        'Nokia 8',
        'HTC 10',
        'Google Pixel',
        'Samsung Galaxy S6 edge+',
        'LG G4',
        'OnePlus 2',
        'Huawei P8',
        'Sony Xperia Z4 Tablet',
        'Nokia Lumia 1520',
        'HTC One M8',
        'Google Nexus 6',
        'Samsung Galaxy S5',
        'LG G3',
        'OnePlus One',
        'Huawei Ascend P6',
        'Sony Xperia Z2',
        'Nokia Lumia 1020',
        'HTC One X',
        'Google Nexus 4',
        'Samsung Galaxy S4',
        'LG Optimus G',
        'OnePlus X',
        'Huawei P7',
        'Sony Xperia Z',
        'Nokia Lumia 920',
        'HTC One S',
        'Google Nexus S',
        'Samsung Galaxy S III',
        'LG Optimus 3D',
        'OnePlus One Mini',
        'Huawei P6',
        'Sony Xperia S',
        'Nokia Lumia 800',
        'HTC Desire HD',
        'Google Nexus One',
        'Samsung Galaxy S II',
        'LG Optimus 2X',
        'OnePlus One Max',
        'Huawei P2',
        'Sony Xperia X10 Mini',
        'Nokia Lumia 710',
        'HTC Wildfire S',
        'Google Nexus S 4G',
        'Samsung Galaxy S',
        'LG Optimus LTE',
        'OnePlus One Mini Dual SIM',
        'Huawei P1',
        'Sony Xperia X8',
        'Nokia Lumia 610',
        'HTC ChaCha',
        'Google Nexus Q',
    ];

    protected $model = AuctionItem::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(self::$goods),
            'description' => fake()->sentence(),
            'starting_price' => fake()->randomNumber(2, false),
            'end_time' => fake()->dateTimeBetween('-1 month', '+1 month')->setTime(23, 59, 59),
        ];
    }
}
