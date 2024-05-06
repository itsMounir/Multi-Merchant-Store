<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use App\Policies\Markets\BillPolicy as MarketbillPolicy;


use App\Policies\Web\EmployeePolicy as WebEmpolyeePolicy;
use App\Policies\Web\BillPolicy as WebBillPolicy;
use App\Policies\web\ProductCategoryPolicy as WebProductCategoryPolicy;
use App\Policies\Web\ProductPolicy as WebProductPolicy;
use App\Policies\Web\CityPolicy as WebCityPolicy;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Bill::class => MarketbillPolicy::class,

        /** Web Policies */
        User::class => WebEmpolyeePolicy::class,
        Bill::class => WebBillPolicy::class,
        Product::class => WebProductPolicy::class,
        ProductCategory::class => WebProductCategoryPolicy::class,
        City::class => WebCityPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
    }
}
