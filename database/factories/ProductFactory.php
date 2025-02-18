<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => 1,
            'name' => fake()->colorName(),
            'image' => fake()->imageUrl(),
            'url' => fake()->url(),
            'specs' => [
                'sku' => fake()->uuid(),
                'upc' => fake()->uuid(),
                'mpn' => fake()->uuid(),
                'brand' => fake()->company(),
            ]
        ];
    }
}
