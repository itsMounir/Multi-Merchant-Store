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
    User,
};
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::insert([
            ['guard_name' => 'web', 'name' => 'owner', 'created_at' => now()],
            ['guard_name' => 'web', 'name' => 'admin', 'created_at' => now()],
            ['guard_name' => 'web', 'name' => 'employee', 'created_at' => now()]
        ]);

        PaymentMethod::insert([
            ['name' => 'كاش', 'created_at' => now()],
            ['name' => 'بطاقة', 'created_at' => now()],
        ]);
        SupplierCategory::insert([
            ['type' => 'مورد', 'created_at' => now()],
            ['type' => 'شركات', 'created_at' => now()],
            ['type' => 'توريدات', 'created_at' => now()]
        ]);

        MarketCategory::insert([
            ["name" => "ميني ماركت", "created_at" => now()],
            ["name" => "سوبر ماركت", "created_at" => now()],
            ["name" => "بقالة جافة", "created_at" => now()],
            ["name" => "عطارة", "created_at" => now()],
            ["name" => "بازار", "created_at" => now()],
            ["name" => "محمصة", "created_at" => now()],
            ["name" => "كشك حلويات", "created_at" => now()],
            ["name" => "محل منظفات", "created_at" => now()],
            ["name" => "كافيتريا", "created_at" => now()],
            ["name" => "مطعم", "created_at" => now()],
            ["name" => "كافيه", "created_at" => now()],
            ["name" => "حلواني", "created_at" => now()],
            ["name" => "فرن افرنجي", "created_at" => now()]
        ]);
        ProductCategory::insert([
            ['name' => 'type-1', 'created_at' => now()],
            ['name' => 'type-2', 'created_at' => now()],
            ['name' => 'type-3', 'created_at' => now()],
            ['name' => 'type-4', 'created_at' => now()],
            ['name' => 'type-5', 'created_at' => now()],
            ['name' => 'type-6', 'created_at' => now()],
        ]);

        User::insert([
            [
                'first_name' => 'Owner',
                'middle_name' => 'Owner',
                'last_name' => 'Owner',
                'phone_number' => '0000000000',
                'email' => 'Owner@gmail.com',
                'password' => Hash::make('password')
            ],
            [
                'first_name' => 'Admin',
                'middle_name' => 'Admin',
                'last_name' => 'Admin',
                'phone_number' => '1111111111',
                'email' => 'Admin@gmail.com',
                'password' => Hash::make('password')
            ]
        ]);
        User::factory(10)->create();

<<<<<<< HEAD
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
=======
        Market::factory(10)->create();
        Supplier::factory(10)->create();
        Product::factory(20)->create();
        Bill::factory(10)->create();
        
        BillProduct::factory(30)->create();
>>>>>>> Admin
    }
}
