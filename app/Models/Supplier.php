<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasPermissions;


class Supplier extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone_number',
        'password',
        'store_name',
        'status',
        'supplier_category_id',
        'delivery_duration',
        'details',
        'min_selling_quantity',
        'location_details',
        'city_id',
        'deviceToken',
    ];

    protected $dates = ['created_at'];


    protected $guard = ['supplier'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Scope a query to only include same-site suppliers.
     */
    public static function scopeSite(Builder $query): void
    {
        $query->whereHas('distributionLocations', function ($query) {
            return $query->where('to_city_id', Auth::user()->city_id);
        });
    }

    public function getMinBillPriceAttribute()
    {
        if (!Auth::check()) {
            return null;
        }

        $distributionLocation = $this->distributionLocations()->where('to_city_id', Auth::user()->city_id)->first();

        if ($distributionLocation) {
            return $distributionLocation->min_bill_price;
        }

        return null;
    }




    public function getCategoryNameAttribute()
    {
        return $this->supplierCategory()->pluck('type')->first();
    }

    public function getCityNameAttribute()
    {
        return $this->city()->pluck('name')->first();
    }

    public function getImageAttribute()
    {
        return asset('storage/Supplier/' . $this->image()->pluck('url')->first());
    }


    public function getImagesAttribute()
    {
        return $this->images()
            ->get(['imageable_type', 'url'])
            ->map(function ($image) {
                $dir = explode('\\', $image->imageable_type)[2];
                unset($image->imageable_type);
                return asset("storage/$dir") . '/' . $image->url;
            });
    }


    public function isActive(): bool
    {
        return ($this->status == 'نشط');
    }


    public static function scopeActive(Builder $query): void
    {
        $query->where('status', 'نشط');
    }


    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function distributionLocations(): HasMany
    {
        return $this->hasMany(DistributionLocation::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
            ->withPivot(
                'id',
                'quantity',
                'price',
                'has_offer',
                'offer_price',
                'max_offer_quantity',
                'offer_expires_at',
                'max_selling_quantity',
                'is_available'
            );
    }

    public function availableProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
            ->withPivot(
                'price',
                'has_offer',
                'offer_price',
                'max_offer_quantity',
                'offer_expires_at',
                'max_selling_quantity',
                'is_available'
            )
            ->where('is_available', true);

    }

    public function productSuppliers(): HasMany
    {
        return $this->hasMany(ProductSupplier::class);
    }

    public function supplierCategory(): BelongsTo
    {
        return $this->belongsTo(SupplierCategory::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    // morphs relation with images table
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function deliveredProductPrice($startDate, $endDate):float
{
    return DB::table('bills')
        ->join('bill_product', 'bills.id', '=', 'bill_product.bill_id')
        ->where('bills.status', 'تم التوصيل')
        ->whereDate('bills.created_at', '>=', $startDate)
        ->whereDate('bills.created_at', '<=', $endDate)
        ->where('bills.supplier_id', $this->id)
        ->select(DB::raw('SUM(
            CASE
                WHEN bill_product.has_offer = 1 THEN
                    (bill_product.offer_buying_price *
                     LEAST(bill_product.max_offer_quantity, bill_product.quantity)) +
                    (bill_product.buying_price *
                     GREATEST(bill_product.quantity - bill_product.max_offer_quantity, 0))
                ELSE
                    (bill_product.buying_price * bill_product.quantity)
            END
        ) as total_price'))
        ->get()
        ->first()
        ->total_price;
}


    public function category()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_category_id');
    }


    public function getMarketsToNotify()
    {
        return Market::where('city_id', $this->city_id)
            ->orWhereIn('city_id', $this->distributionLocations->pluck('to_city_id'))
            ->get()
            ->unique('id');
    }

    /**
     * Get the recent notifications for the supplier.
     *
     * @param int $count Number of notifications to retrieve
     */
    public function getNotifications()
    {

        $notifications = $this->notifications()->whereIn('type', ['new-bill', 'preparing-bill'])->whereNull('read_at')->get();

        return $notifications->values();

    }

    public function count_product(){
        return $this->productSuppliers()->where('is_available','1')->count();
    }

}
