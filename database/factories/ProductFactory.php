<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'code' => fake()->name(),
            'category' => fake()->name,
            'unit_price' => fake()->numberBetween(1, 10000000),
        ];
    }
}
