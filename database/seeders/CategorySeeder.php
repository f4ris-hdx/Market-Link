<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Vegetables', 'slug' => 'vegetables', 'icon' => 'fa-carrot'],
            ['name' => 'Fruits', 'slug' => 'fruits', 'icon' => 'fa-apple-whole'],
            ['name' => 'Dairy & Eggs', 'slug' => 'dairy-eggs', 'icon' => 'fa-cow'],
            ['name' => 'Baked Goods', 'slug' => 'baked-goods', 'icon' => 'fa-bread-slice'],
            ['name' => 'Herbs & Spices', 'slug' => 'herbs-spices', 'icon' => 'fa-leaf'],
            ['name' => 'Honey & Preserves', 'slug' => 'honey-preserves', 'icon' => 'fa-jar-wheat'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
