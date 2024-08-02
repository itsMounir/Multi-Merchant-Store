<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissions, HasRoles;

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
        'email',
        'last_activity',
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<int, string>
     */
    protected $appends = ['isOnline'];


    protected $dates = ['created_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deviceToken',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'date:Y-m-d',
        'last_activity' => 'datetime',
    ];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getIsOnlineAttribute()
    {
        return $this->last_activity && $this->last_activity->gt(Carbon::now()->subMinutes(5));
    }
}
