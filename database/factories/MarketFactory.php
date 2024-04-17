<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market>
 */
class MarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'first_name'=> fake()->firstName(),
            'middle_name'=> fake()->firstName(),
            'last_name'=> fake()->lastName(),
            'phone_number'=>fake()->phoneNumber(),
            'store_name'=>fake()->name(),
            'city'=>fake()->city(),
            'street'=>fake()->streetAddress(),
            'is_subscribed'=>0,
            'password'=>Hash::make('password'),
            'market_category_id' => 1,
            'subscription_expires_at'=>fake()->date('Y-m-d'),
            'status'=>fake()->randomElement(['نشط','محظور','غير نشط']),
        ];
    }
}
