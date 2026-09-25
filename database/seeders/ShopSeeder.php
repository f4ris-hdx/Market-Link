<?php

namespace Database\Seeders;

use App\Models\Market;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $shops = [
            ['market' => 'Central Farmers Hub', 'name' => 'Green Valley Farm', 'location' => 'Stall A1, Downtown Plaza'],
            ['market' => 'Central Farmers Hub', 'name' => 'Sunny Acres Dairy', 'location' => 'Stall A2, Downtown Plaza'],
            ['market' => 'Central Farmers Hub', 'name' => 'The Oat Barn', 'location' => 'Stall A3, Downtown Plaza'],
            ['market' => 'Green Valley Eco Market', 'name' => 'Willow Creek Produce', 'location' => 'Stall B1, Northside Park'],
            ['market' => 'Green Valley Eco Market', 'name' => 'Harvest Hills Farm', 'location' => 'Stall B2, Northside Park'],
            ['market' => 'West End Organics Fair', 'name' => 'Golden Hive Apiary', 'location' => 'Stall C1, Community Center'],
            ['market' => 'West End Organics Fair', 'name' => 'The Herb Patch', 'location' => 'Stall C2, Community Center'],
            ['market' => 'West End Organics Fair', 'name' => 'Spice Mill Collective', 'location' => 'Stall C3, Community Center'],
            ['market' => 'Riverside Sunday Market', 'name' => 'Meadow Creek Dairy', 'location' => 'Stall D1, Riverfront Promenade'],
            ['market' => 'Riverside Sunday Market', 'name' => 'Oak Grove Kitchen', 'location' => 'Stall D2, Riverfront Promenade'],
            ['market' => 'Harvest Square Hub', 'name' => 'Heritage Orchard', 'location' => 'Stall E1, Old Town Square'],
            ['market' => 'Harvest Square Hub', 'name' => 'Rustic Loaf Bakery', 'location' => 'Stall E2, Old Town Square'],
            ['market' => 'Harvest Square Hub', 'name' => 'Blueberry Hill Farm', 'location' => 'Stall E3, Old Town Square'],
            ['market' => 'Harvest Square Hub', 'name' => 'Citrus Ridge Farm', 'location' => 'Stall E4, Old Town Square'],
        ];

        foreach ($shops as $shop) {
            $marketId = Market::where('name', $shop['market'])->value('id');
            if (! $marketId) {
                continue;
            }

            Shop::updateOrCreate(
                ['market_id' => $marketId, 'name' => $shop['name']],
                ['location' => $shop['location'], 'status' => 'active'],
            );
        }
    }
}
