<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Supplier extends Model
{
    use HasFactory;

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
        'store_name',
        'status',
    ];

    public function image() : MorphOne {
        return $this->morphOne(Image::class,'imageable');
    }

    public function distributionLocations() : HasMany {
        return $this->hasMany(DistributinLocation::class);
    }

    public function bills() : HasMany {
        return $this->hasMany(Bill::class);
    }

    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class);
    }
}
