<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            MarketSeeder::class,
            FarmerSeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
        ]);
    }
}
