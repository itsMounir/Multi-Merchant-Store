<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{
    ProductCategory,
    MarketCategory,
    Product,
    PayementMethod
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PayementMethod::create([
            'name' => 'كاش'
        ]);

        PayementMethod::create([
            'name' => 'بطاقة'
        ]);

        ProductCategory::create([
            'name' => 'product category',
        ]);
        MarketCategory::create([
            'name' => 'market category',
        ]);
        Product::factory(20)->create();
        // \App\Models\User::factory(10)->create();
    }
}
