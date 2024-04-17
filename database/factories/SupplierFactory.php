<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone_number' => fake()->phoneNumber(),
            'store_name' => fake()->name(),
            'password' => Hash::make('password'),
            'supplier_category_id' => fake()->randomElement([1, 2]),
            'min_bill_price' => fake()->randomFloat(3, 250.0, 5000),
            'min_selling_quantity' => fake()->randomFloat(3, 250.0, 5000),
            'status' => fake()->randomElement(['نشط', 'محظور', 'غير نشط']),
        ];
    }
}
