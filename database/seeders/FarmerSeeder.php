<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\Market;
use Illuminate\Database\Seeder;

class FarmerSeeder extends Seeder
{
    public function run(): void
    {
        $farmers = [
            ['name' => 'Green Valley Farm', 'location' => 'North District', 'specialty' => 'Organic Heirloom Vegetables & Herbs', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=500&q=80', 'market' => 'Central Farmers Hub'],
            ['name' => 'Sunny Acres Dairy', 'location' => 'East Valley', 'specialty' => 'Raw Milk, Cheese & Pasture Eggs', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1527153857715-3908f2bae5e8?auto=format&fit=crop&w=500&q=80', 'market' => 'Central Farmers Hub'],
            ['name' => 'Heritage Orchard', 'location' => 'South Hills', 'specialty' => 'Seasonal Apples, Peaches & Pears', 'rating' => 5.0, 'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=500&q=80', 'market' => 'Harvest Square Hub'],
            ['name' => 'Rustic Loaf Bakery', 'location' => 'Old Town', 'specialty' => 'Sourdough & Artisanal Pastries', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?auto=format&fit=crop&w=500&q=80', 'market' => 'Harvest Square Hub'],
            ['name' => 'Golden Hive Apiary', 'location' => 'West Valley', 'specialty' => 'Raw Wildflower Honey & Honeycomb', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=500&q=80', 'market' => 'West End Organics Fair'],
            ['name' => 'Willow Creek Produce', 'location' => 'North District', 'specialty' => 'Leafy Greens & Summer Vegetables', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&w=500&q=80', 'market' => 'Green Valley Eco Market'],
            ['name' => 'Meadow Creek Dairy', 'location' => 'East Valley', 'specialty' => 'Artisan Cheeses & Yogurts', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1527153857715-3908f2bae5e8?auto=format&fit=crop&w=500&q=80', 'market' => 'Riverside Sunday Market'],
            ['name' => 'Blueberry Hill Farm', 'location' => 'South Hills', 'specialty' => 'Wild Blueberries & Soft Fruit', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=500&q=80', 'market' => 'Harvest Square Hub'],
            ['name' => 'Harvest Hills Farm', 'location' => 'West Valley', 'specialty' => 'Field Vegetables & Sweet Corn', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=500&q=80', 'market' => 'Green Valley Eco Market'],
            ['name' => 'The Herb Patch', 'location' => 'Downtown', 'specialty' => 'Fresh Culinary Herbs & Microgreens', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1608683222718-406a6b5711e5?auto=format&fit=crop&w=500&q=80', 'market' => 'West End Organics Fair'],
            ['name' => 'The Oat Barn', 'location' => 'North District', 'specialty' => 'Granola, Oats & Baked Staples', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1517679284304-f76e6fdc1243?auto=format&fit=crop&w=500&q=80', 'market' => 'Central Farmers Hub'],
            ['name' => 'Citrus Ridge Farm', 'location' => 'South Hills', 'specialty' => 'Oranges & Mediterranean Fruit', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab5b?auto=format&fit=crop&w=500&q=80', 'market' => 'Harvest Square Hub'],
            ['name' => 'Oak Grove Kitchen', 'location' => 'Old Town', 'specialty' => 'Small-Batch Jams & Preserves', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=500&q=80', 'market' => 'Riverside Sunday Market'],
            ['name' => 'Spice Mill Collective', 'location' => 'Downtown', 'specialty' => 'Single-Origin Ground Spices', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?auto=format&fit=crop&w=500&q=80', 'market' => 'West End Organics Fair'],
        ];

        foreach ($farmers as $farmer) {
            Farmer::firstOrCreate(
                ['name' => $farmer['name']],
                [
                    'location' => $farmer['location'],
                    'specialty' => $farmer['specialty'],
                    'rating' => $farmer['rating'],
                    'image' => $farmer['image'],
                    'market_id' => Market::where('name', $farmer['market'])->value('id'),
                    'status' => 'verified',
                    'slots' => 'Sat 8:00-10:00 AM, Sat 10:30-12:30 PM, Sun 9:00-11:00 AM',
                ],
            );
        }

        Farmer::whereNull('user_id')->update(['is_demo' => true]);
    }
}
