<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Review> */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'product_id' => null,
            'farmer_id' => null,
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->sentence(),
            'status' => 'published',
        ];
    }
}
