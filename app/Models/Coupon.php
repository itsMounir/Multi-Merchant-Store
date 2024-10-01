<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'min_bill_limit',
        'disscount_value',
        'active',
    ];

    /**
     * Scope a query to only include same-site suppliers.
     */
    public static function scopeActive(Builder $query): void
    {
        $query->where('active',true);
    }


    public function bill(): HasMany
    {
        return $this->hasMany(CouponBill::class);
    }
}
