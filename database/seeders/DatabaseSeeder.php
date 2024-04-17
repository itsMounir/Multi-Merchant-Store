<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{
    Market,
    ProductCategory,
    MarketCategory,
    Product,
    PayementMethod,
    SupplierCategory,
    Supplier,
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

        SupplierCategory::create([
            'type' => 'مورد',
        ]);

        SupplierCategory::create([
            'type' => 'شركات',
        ]);

        SupplierCategory::create([
            'type' => 'توريدات',
        ]);

        ProductCategory::create([
            'name' => 'product category',
        ]);
        MarketCategory::create([
            'name' => 'market category',
        ]);
        Product::factory(20)->create();
        Market::factory(10)->create();
        Supplier::factory(10)->create();
        // \App\Models\User::factory(10)->create();
    }
}
