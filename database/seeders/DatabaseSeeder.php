<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Domain\Entity\AuctionItem;
use App\Domain\Entity\Bid;
use App\Domain\Entity\Enum\UserTypeEnum;
use App\Domain\Entity\User;
use App\Domain\Entity\UserAuctionAutobid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->defaultUser();
        $this->seedAuctionItem();
    }

    private function defaultUser(): void
    {
        User::factory()->state([
            'username' => 'admin1',
            'password' => Hash::make('password'),
            'type' => UserTypeEnum::admin,
        ])->create();
        User::factory()->state([
            'username' => 'admin2',
            'password' => Hash::make('password'),
            'type' => UserTypeEnum::admin,
        ])->create();
        User::factory()->state([
            'username' => 'user1',
            'password' => Hash::make('password'),
            'type' => UserTypeEnum::regular,
        ])->create();
        User::factory()->state([
            'username' => 'user2',
            'password' => Hash::make('password'),
            'type' => UserTypeEnum::regular,
        ])->create();
    }

    private function seedAuctionItem(): void
    {
        AuctionItem::factory(10)->create();
    }
}
