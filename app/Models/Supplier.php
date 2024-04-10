<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;


class Supplier extends Authenticatable
{
    use HasFactory,HasApiTokens,Notifiable,HasPermissions;

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
        'type',
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
        return $this->belongsToMany(Product::class,'product_suppliers');
    }



}
