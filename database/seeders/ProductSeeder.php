<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Farmer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $img = [
            'tomatoes' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80',
            'carrots' => 'https://images.unsplash.com/photo-1447175008436-054170c2e979?auto=format&fit=crop&w=600&q=80',
            'milk' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=600&q=80',
            'apples' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80',
            'bread' => 'https://images.unsplash.com/photo-1585478259715-876acc5be8eb?auto=format&fit=crop&w=600&q=80',
            'herbs' => 'https://images.unsplash.com/photo-1608683222718-406a6b5711e5?auto=format&fit=crop&w=600&q=80',
            'eggs' => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?auto=format&fit=crop&w=600&q=80',
            'honey' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?auto=format&fit=crop&w=600&q=80',
            'berries' => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=600&q=80',
            'basket' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=600&q=80',
            'vegmarket' => 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=600&q=80',
            'orange' => 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab5b?auto=format&fit=crop&w=600&q=80',
            'cheese' => 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?auto=format&fit=crop&w=600&q=80',
            'yogurt' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80',
            'pastry' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80',
        ];

        $products = [
            ['name' => 'Organic Heirloom Tomatoes', 'category' => 'Vegetables', 'farmer' => 'Green Valley Farm', 'price' => 4.50, 'unit' => 'lb', 'stock' => 25, 'rating' => 4.9, 'image' => $img['tomatoes']],
            ['name' => 'Rainbow Carrots Bundle', 'category' => 'Vegetables', 'farmer' => 'Willow Creek Produce', 'price' => 3.20, 'unit' => 'bunch', 'stock' => 30, 'rating' => 4.8, 'image' => $img['carrots']],
            ['name' => 'Curly Kale Greens', 'category' => 'Vegetables', 'farmer' => 'Green Valley Farm', 'price' => 3.00, 'unit' => 'bunch', 'stock' => 22, 'rating' => 4.7, 'image' => $img['vegmarket']],
            ['name' => 'Sweet Corn Pack (4)', 'category' => 'Vegetables', 'farmer' => 'Harvest Hills Farm', 'price' => 5.00, 'unit' => 'pack', 'stock' => 18, 'rating' => 4.8, 'image' => $img['vegmarket']],
            ['name' => 'Shishito Peppers', 'category' => 'Vegetables', 'farmer' => 'Willow Creek Produce', 'price' => 4.00, 'unit' => 'lb', 'stock' => 20, 'rating' => 4.6, 'image' => $img['basket']],
            ['name' => 'Heirloom Beets', 'category' => 'Vegetables', 'farmer' => 'Green Valley Farm', 'price' => 3.75, 'unit' => 'lb', 'stock' => 14, 'rating' => 4.7, 'image' => $img['carrots']],
            ['name' => 'Crisp Honeycrisp Apples', 'category' => 'Fruits', 'farmer' => 'Heritage Orchard', 'price' => 3.80, 'unit' => 'lb', 'stock' => 40, 'rating' => 5.0, 'image' => $img['apples']],
            ['name' => 'Sweet Summer Strawberries', 'category' => 'Fruits', 'farmer' => 'Willow Creek Produce', 'price' => 6.20, 'unit' => 'pint', 'stock' => 6, 'rating' => 4.9, 'image' => $img['berries']],
            ['name' => 'Sun-Ripened Peaches', 'category' => 'Fruits', 'farmer' => 'Heritage Orchard', 'price' => 5.50, 'unit' => 'lb', 'stock' => 16, 'rating' => 4.9, 'image' => $img['basket']],
            ['name' => 'Wild Blueberries', 'category' => 'Fruits', 'farmer' => 'Blueberry Hill Farm', 'price' => 7.25, 'unit' => 'pint', 'stock' => 12, 'rating' => 4.9, 'image' => $img['berries']],
            ['name' => 'Navel Oranges', 'category' => 'Fruits', 'farmer' => 'Citrus Ridge Farm', 'price' => 4.20, 'unit' => 'lb', 'stock' => 24, 'rating' => 4.7, 'image' => $img['orange']],
            ['name' => 'Bartlett Pears', 'category' => 'Fruits', 'farmer' => 'Heritage Orchard', 'price' => 4.75, 'unit' => 'lb', 'stock' => 18, 'rating' => 4.8, 'image' => $img['apples']],
            ['name' => 'Farm Fresh Whole Milk', 'category' => 'Dairy & Eggs', 'farmer' => 'Sunny Acres Dairy', 'price' => 5.20, 'unit' => 'half-gal', 'stock' => 12, 'rating' => 4.8, 'image' => $img['milk']],
            ['name' => 'Pasture-Raised Eggs (dozen)', 'category' => 'Dairy & Eggs', 'farmer' => 'Sunny Acres Dairy', 'price' => 6.50, 'unit' => 'dozen', 'stock' => 15, 'rating' => 5.0, 'image' => $img['eggs']],
            ['name' => 'Aged White Cheddar', 'category' => 'Dairy & Eggs', 'farmer' => 'Meadow Creek Dairy', 'price' => 9.00, 'unit' => 'lb', 'stock' => 10, 'rating' => 4.9, 'image' => $img['cheese']],
            ['name' => 'Thick Greek Yogurt', 'category' => 'Dairy & Eggs', 'farmer' => 'Meadow Creek Dairy', 'price' => 7.50, 'unit' => 'tub', 'stock' => 9, 'rating' => 4.7, 'image' => $img['yogurt']],
            ['name' => 'Cultured Butter', 'category' => 'Dairy & Eggs', 'farmer' => 'Sunny Acres Dairy', 'price' => 8.25, 'unit' => 'lb', 'stock' => 8, 'rating' => 4.8, 'image' => $img['cheese']],
            ['name' => 'Artisanal Sourdough Loaf', 'category' => 'Baked Goods', 'farmer' => 'Rustic Loaf Bakery', 'price' => 7.00, 'unit' => 'loaf', 'stock' => 10, 'rating' => 4.7, 'image' => $img['bread']],
            ['name' => 'Baguette 2-Pack', 'category' => 'Baked Goods', 'farmer' => 'Rustic Loaf Bakery', 'price' => 5.50, 'unit' => 'pack', 'stock' => 14, 'rating' => 4.6, 'image' => $img['pastry']],
            ['name' => 'Butter Croissants (4)', 'category' => 'Baked Goods', 'farmer' => 'Rustic Loaf Bakery', 'price' => 6.75, 'unit' => 'box', 'stock' => 11, 'rating' => 4.8, 'image' => $img['pastry']],
            ['name' => 'Cinnamon Rolls (3)', 'category' => 'Baked Goods', 'farmer' => 'Rustic Loaf Bakery', 'price' => 8.50, 'unit' => 'box', 'stock' => 7, 'rating' => 4.9, 'image' => $img['pastry']],
            ['name' => 'Homemade Granola', 'category' => 'Baked Goods', 'farmer' => 'The Oat Barn', 'price' => 6.40, 'unit' => 'jar', 'stock' => 13, 'rating' => 4.6, 'image' => $img['yogurt']],
            ['name' => 'Fresh Organic Basil', 'category' => 'Herbs & Spices', 'farmer' => 'Green Valley Farm', 'price' => 2.50, 'unit' => 'bunch', 'stock' => 18, 'rating' => 4.9, 'image' => $img['herbs']],
            ['name' => 'Garden Mint', 'category' => 'Herbs & Spices', 'farmer' => 'The Herb Patch', 'price' => 2.20, 'unit' => 'bunch', 'stock' => 20, 'rating' => 4.6, 'image' => $img['herbs']],
            ['name' => 'Fresh Thyme', 'category' => 'Herbs & Spices', 'farmer' => 'The Herb Patch', 'price' => 2.40, 'unit' => 'bunch', 'stock' => 16, 'rating' => 4.7, 'image' => $img['herbs']],
            ['name' => 'Rosemary Bundle', 'category' => 'Herbs & Spices', 'farmer' => 'The Herb Patch', 'price' => 2.60, 'unit' => 'bunch', 'stock' => 15, 'rating' => 4.7, 'image' => $img['herbs']],
            ['name' => 'Ground Turmeric (2oz)', 'category' => 'Herbs & Spices', 'farmer' => 'Spice Mill Collective', 'price' => 5.80, 'unit' => 'jar', 'stock' => 10, 'rating' => 4.8, 'image' => $img['honey']],
            ['name' => 'Raw Wildflower Honey', 'category' => 'Honey & Preserves', 'farmer' => 'Golden Hive Apiary', 'price' => 9.75, 'unit' => 'jar', 'stock' => 8, 'rating' => 4.8, 'image' => $img['honey']],
            ['name' => 'Honeycomb Gift Box', 'category' => 'Honey & Preserves', 'farmer' => 'Golden Hive Apiary', 'price' => 12.50, 'unit' => 'box', 'stock' => 6, 'rating' => 5.0, 'image' => $img['honey']],
            ['name' => 'Strawberry Chia Jam', 'category' => 'Honey & Preserves', 'farmer' => 'Oak Grove Kitchen', 'price' => 7.20, 'unit' => 'jar', 'stock' => 14, 'rating' => 4.6, 'image' => $img['honey']],
            ['name' => 'Peach Preserves', 'category' => 'Honey & Preserves', 'farmer' => 'Oak Grove Kitchen', 'price' => 7.50, 'unit' => 'jar', 'stock' => 12, 'rating' => 4.7, 'image' => $img['honey']],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                [
                    'farmer_id' => Farmer::where('name', $product['farmer'])->value('id'),
                    'category_id' => Category::where('name', $product['category'])->value('id'),
                    'unit' => $product['unit'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'rating' => $product['rating'],
                    'image' => $product['image'],
                ],
            );
        }
    }
}
