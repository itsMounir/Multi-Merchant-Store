<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\City;
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
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class DatabaseSeeder extends Seeder
{
    use HasRoles;


    private function rolesAndPermissions()
    {
        $superAdminRole = Role::query()->where('name', 'super_admin')->first();
        $adminRole = Role::query()->where('name', 'admin')->first();
        $supervisorRole = Role::query()->where('name', 'supervisor')->first();
        $moderatorRole = Role::query()->where('name', 'moderator')->first();
        $dataEntryRole = Role::query()->where('name', 'data_entry')->first();

        $superAdminRole->givePermissionTo([
            'user-stuff',
            'bill-stuff',
            'product-stuff',
            'city-stuff',
        ]);

        $adminRole->givePermissionTo([
            'user-stuff',
            'bill-stuff',
            'product-stuff',
            'city-stuff',
        ]);

        $superAdminUser = User::find(1);
        $adminUser = User::find(2);
        $supervisor = User::find(3);
        $moderator = User::find(4);
        $data_entry = User::find(5);

        $superAdminUser->assignRole($superAdminRole);
        $adminUser->assignRole($adminRole);
        $supervisor->assignRole($supervisorRole);
        $moderator->assignRole($moderatorRole);
        $data_entry->assignRole($dataEntryRole);
    }

    private function cities()
    {
        /** Big Cities */
        city::insert([
            ['name' => 'الأسكندرية', 'parent_id' => null, 'created_at' => now()],

        ]);

        /** Small cities*/
        City::insert([
            ["name" => "ابوقير", "parent_id" => 1, "created_at" => now()],
            ["name" => "الاصلاح", "parent_id" => 1, "created_at" => now()],
            ["name" => "طوسون", "parent_id" => 1, "created_at" => now()],
            ["name" => "المعمورة", "parent_id" => 1, "created_at" => now()],
            ["name" => "المنتزه", "parent_id" => 1, "created_at" => now()],
            ["name" => "المندرة", "parent_id" => 1, "created_at" => now()],
            ["name" => "العماروة", "parent_id" => 1, "created_at" => now()],
            ["name" => "الملاحة", "parent_id" => 1, "created_at" => now()],
            ["name" => "العصافرة", "parent_id" => 1, "created_at" => now()],
            ["name" => "شارع الجيش", "parent_id" => 1, "created_at" => now()],
            ["name" => "سيدي بشر", "parent_id" => 1, "created_at" => now()],
            ["name" => "سيدي بشر - مدينة بنك فيصل", "parent_id" => 1, "created_at" => now()],
            ["name" => "شارع 15", "parent_id" => 1, "created_at" => now()],
            ["name" => "دربالة", "parent_id" => 1, "created_at" => now()],
            ["name" => "فيكتوريا", "parent_id" => 1, "created_at" => now()],
            ["name" => "الساعة", "parent_id" => 1, "created_at" => now()],
            ["name" => "ابو سلیمان", "parent_id" => 1, "created_at" => now()],
            ["name" => "غبريال", "parent_id" => 1, "created_at" => now()],
            ["name" => "اكوس", "parent_id" => 1, "created_at" => now()],
            ["name" => "العوايد", "parent_id" => 1, "created_at" => now()],
            ["name" => "الزوايدة", "parent_id" => 1, "created_at" => now()],
            ["name" => "خورشید", "parent_id" => 1, "created_at" => now()],
            ["name" => "سموحة", "parent_id" => 1, "created_at" => now()],
            ["name" => "مطار سموحة", "parent_id" => 1, "created_at" => now()],
            ["name" => "الظاهرية", "parent_id" => 1, "created_at" => now()],
            ["name" => "سيدي جابر", "parent_id" => 1, "created_at" => now()],
            ["name" => "جناكليس", "parent_id" => 1, "created_at" => now()],
            ["name" => "رشدي", "parent_id" => 1, "created_at" => now()],
            ["name" => "بولکلی", "parent_id" => 1, "created_at" => now()],
            ["name" => "الإبراهيمية", "parent_id" => 1, "created_at" => now()],
            ["name" => "سبورتننج", "parent_id" => 1, "created_at" => now()],
            ["name" => "كليوباترا", "parent_id" => 1, "created_at" => now()],
            ["name" => "الحضرة الجديدة", "parent_id" => 1, "created_at" => now()],
            ["name" => "الحضرة القديمة", "parent_id" => 1, "created_at" => now()],
            ["name" => "الشاطبي", "parent_id" => 1, "created_at" => now()],
            ["name" => "كامب شيزار", "parent_id" => 1, "created_at" => now()],
            ["name" => "حجر النواتيه", "parent_id" => 1, "created_at" => now()],
            ["name" => "محطة الرمل", "parent_id" => 1, "created_at" => now()],
            ["name" => "محطة مصر", "parent_id" => 1, "created_at" => now()],
            ["name" => "المنشية", "parent_id" => 1, "created_at" => now()],
            ["name" => "بحري", "parent_id" => 1, "created_at" => now()],
            ["name" => "الانفوشي", "parent_id" => 1, "created_at" => now()],
            ["name" => "رأس التين", "parent_id" => 1, "created_at" => now()],
            ["name" => "الورديان", "parent_id" => 1, "created_at" => now()],
            ["name" => "الدخيلة", "parent_id" => 1, "created_at" => now()],
            ["name" => "البيطاش", "parent_id" => 1, "created_at" => now()],
            ["name" => "الهانوفيل", "parent_id" => 1, "created_at" => now()],
            ["name" => "أبو يوسف", "parent_id" => 1, "created_at" => now()]
        ]);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

          Role::insert([
            ['guard_name' => 'web', 'name' => 'super_admin', 'created_at' => now()],
            ['guard_name' => 'web', 'name' => 'admin', 'created_at' => now()],
            ['guard_name' => 'web', 'name' => 'supervisor', 'created_at' => now()],     // الإشراف على الحسابات
            ['guard_name' => 'web', 'name' => 'moderator', 'created_at' => now()],      // الإشراف على الفواتير
            ['guard_name' => 'web', 'name' => 'data_entry', 'created_at' => now()],     // الإشراف على إدخال البيانات
        ]);

        Permission::insert([
            ['name' => 'user-stuff', 'guard_name' => 'web', 'created_at' => now()],
            // Deal with user's account - accept & cancel & ban user - user's category
            ['name' => 'bill-stuff', 'guard_name' => 'web', 'created_at' => now()],
            // Deal with bill's Business - accept and cancel Bill - bill's category
            ['name' => 'product-stuff', 'guard_name' => 'web', 'created_at' => now()],
            // Deal with product's Business - CRUD - product's category
            ['name' => 'city-stuff', 'guard_name' => 'web', 'created_at' => now()],
            // Deal with city's Business - CRUD - city's category
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
                'phone_number' => '+201000000000',
                'email' => 'Owner@gmail.com',
                'password' => Hash::make('password')
            ],
            [
                'first_name' => 'Admin',
                'middle_name' => 'Admin',
                'last_name' => 'Admin',
                'phone_number' => '+201111111111',
                'email' => 'Admin@gmail.com',
                'password' => Hash::make('password')
            ],
            [
                'first_name' => 'SS',
                'middle_name' => 'SS',
                'last_name' => 'SS',
                'phone_number' => '+201222222222',
                'email' => 'ss@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'MM',
                'middle_name' => 'MM',
                'last_name' => 'MM',
                'phone_number' => '+201333333333',
                'email' => 'mm@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'DD',
                'middle_name' => 'DD',
                'last_name' => 'DD',
                'phone_number' => '+201444444444',
                'email' => 'dd@gmail.com',
                'password' => Hash::make('password'),
            ],
        ]);
        // User::factory(10)->create();
        $this->cities();
        Market::factory(10)->create();
        Supplier::factory(10)->create();
        Product::factory(20)->create();
        // Bill::factory(10)->create();
        // BillProduct::factory(5)->create();
          $this->rolesAndPermissions();

    }
}
