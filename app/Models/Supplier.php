<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;


class Supplier extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasPermissions;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        //static::addGlobalScope(new ActiveScope);
    }

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
        'discount_code',
        'discount_by_code',
        'store_name',
        'status',
        'supplier_category_id',
    ];

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
        //'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Scope a query to only include same-site suppliers.
     */
    public static function scopeSite(Builder $query): void
    {
        $query->whereHas('distributionLocations', function ($query) {
            return $query->where('to_site', Auth::user()->city);
        });
    }


    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function distributionLocations(): HasMany
    {
        return $this->hasMany(DistributinLocation::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'product_supplier')
            ->withPivot('price','price_after_sales');

    }

    public function productSuppliers() : HasMany {
        return $this->hasMany(ProductSupplier::class);
    }

    public function category() : BelongsTo {
        return $this->belongsTo(Supplier_Category::class);
    }

}

