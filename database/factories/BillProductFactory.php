<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BillProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bills = Bill::pluck('id')->toArray();
        $products = Product::pluck('id')->toArray();

        return [
            'bill_id' => fake()->randomElement($bills),
            'product_id' => fake()->randomElement($products),
            'quantity' => fake()->numberBetween(1, 10)
        ];
    }
}
