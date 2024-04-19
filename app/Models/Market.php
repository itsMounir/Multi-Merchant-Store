<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;


class Market extends Authenticatable
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
        'city',
        'street',
        'market_category_id',
        'representator_code',
        'is_subscribed',
        'store_name',
        'subscription_expires_at',
    ];

    protected $guard = 'market';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
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

    protected $appends = ['created_from', 'images'];

    // created from attribute
    public function getCreatedFromAttribute()
    {
        //return $this->created_at->diffForHumans();
    }

    // images attribute
    public function getImagesAttribute()
    {
        return $this->images()
            ->get(['imageable_type', 'url'])
            ->map(function ($image) {
                $dir = explode('\\', $image->imageable_type)[2];
                unset ($image->imageable_type);
                return asset("public/$dir") . '/' . $image->url;
            });
    }



    public function isActive(): bool
    {
        return ($this->status == 'نشط');
    }


    // morphs relation with images table
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MarketCategory::class);
    }

    public function goals(): BelongsToMany
    {
        return $this->belongsToMany(Goal::class)->withTimestamps();
    }
}
