<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\City;
use App\Models\Market;
use App\Models\MarketCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\SupplierCategory;
use App\Models\User;
use App\Policies\Markets\BillPolicy;


use App\Policies\Web\EmployeePolicy as WebEmpolyeePolicy;
use App\Policies\Web\ProductCategoryPolicy as WebProductCategoryPolicy;
use App\Policies\Web\ProductPolicy as WebProductPolicy;
use App\Policies\Web\CityPolicy as WebCityPolicy;
use App\Policies\Web\MarketPolicy as WebMarketPolicy;
use App\Policies\Web\MarketCategoryPolicy as WebMarketCategoryPolicy;
use App\Policies\Web\SupplierPolicy as WebSupplierPolicy;
use App\Policies\Web\SupplierCategoryPolicy as WebSupplierCategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Bill::class => BillPolicy::class,

            /** Web Policies */
        User::class => WebEmpolyeePolicy::class,
        Product::class => WebProductPolicy::class,
        ProductCategory::class => WebProductCategoryPolicy::class,
        City::class => WebCityPolicy::class,
        Market::class => WebMarketPolicy::class,
        MarketCategory::class => WebMarketCategoryPolicy::class,
        Supplier::class => WebSupplierPolicy::class,
        SupplierCategory::class => WebSupplierCategoryPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
    }
}
