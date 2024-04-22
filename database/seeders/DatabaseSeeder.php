<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{
    Bill,
    BillProduct,
    Market,
    ProductCategory,
    MarketCategory,
    Product,
    PaymentMethod,
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
        PaymentMethod::create([
            'name' => 'كاش'
        ]);

        PaymentMethod::create([
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
        // Market::factory(10)->create();
        // Supplier::factory(10)->create();
        // Product::factory(20)->create();
        // Bill::factory(50)->create();
        // BillProduct::factory(100)->create();
        // \App\Models\User::factory(10)->create();
    }
}
