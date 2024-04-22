<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $category = ProductCategory::pluck('id')->toArray();
        return [

            'product_category_id' => fake()->randomElement($category),
            'name' => fake()->firstName(),
            'discription' => fake()->text(100),
            'size' => fake()->numberBetween(1, 10),
            'size_of' => fake()->word(),
        ];
    }
}
