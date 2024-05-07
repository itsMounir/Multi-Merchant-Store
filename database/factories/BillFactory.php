<?php

namespace Database\Factories;

use App\Models\Market;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $markets = Market::pluck('id')->toArray();
        $suppliers = Supplier::pluck('id')->toArray();
        return [
            'total_price' => fake()->randomNumber(5, true),
            'recieved_price' => fake()->randomNumber(5),
            'payment_method_id' => fake()->randomElement([1, 2]),
            'status' => fake()->randomElement(['انتظار', 'جديد', 'ملغية', 'تم التوصيل', 'قيد التحضير', 'رفض الاستلام']),
            'market_id' => fake()->randomElement($markets),
            'supplier_id' => fake()->randomElement($suppliers),
            'market_note' => fake()->paragraph(1),
            'has_additional_cost' => fake()->randomElement([0, 1]),
        ];
    }
}
