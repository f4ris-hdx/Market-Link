<?php

namespace Database\Factories;

use App\Models\FavoriteFarmer;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<FavoriteFarmer> */
class FavoriteFarmerFactory extends Factory
{
    protected $model = FavoriteFarmer::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'farmer_id' => 1,
        ];
    }
}
