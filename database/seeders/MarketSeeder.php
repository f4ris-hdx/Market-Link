<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        $markets = [
            ['name' => 'Central Farmers Hub', 'location' => 'Downtown Plaza', 'days' => 'Saturdays, 8:00 AM - 1:00 PM', 'farmers_count' => 14, 'distance' => 1.2],
            ['name' => 'Green Valley Eco Market', 'location' => 'Northside Park', 'days' => 'Sundays, 9:00 AM - 2:00 PM', 'farmers_count' => 9, 'distance' => 3.5],
            ['name' => 'West End Organics Fair', 'location' => 'Community Center', 'days' => 'Wednesdays, 3:00 PM - 7:00 PM', 'farmers_count' => 11, 'distance' => 4.1],
            ['name' => 'Riverside Sunday Market', 'location' => 'Riverfront Promenade', 'days' => 'Sundays, 8:00 AM - 12:00 PM', 'farmers_count' => 7, 'distance' => 5.2],
            ['name' => 'Harvest Square Hub', 'location' => 'Old Town Square', 'days' => 'Saturdays, 9:00 AM - 1:00 PM', 'farmers_count' => 16, 'distance' => 2.4],
            ['name' => 'Summer Nights Bazaar', 'location' => 'Harbor Green', 'days' => 'Thursdays, 5:00 PM - 9:00 PM', 'farmers_count' => 6, 'distance' => 6.4],
        ];

        foreach ($markets as $market) {
            Market::firstOrCreate(['name' => $market['name']], $market);
        }
    }
}
