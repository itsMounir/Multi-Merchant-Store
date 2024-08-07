<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'size',
        'size_of',
        'discription',
        'product_category_id',
    ];

    protected $dates = ['created_at', 'deleted_at'];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<int, string>
     */
    protected $appends = ['image', 'product_category'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];


    public function getProductCategoryAttribute()
    {
        return $this->category()->pluck('name')->first();
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot(
                'id',
                'quantity',
                'price',
                'has_offer',
                'offer_price',
                'max_offer_quantity',
                'offer_expires_at',
            );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getImageAttribute()
    {
        return $this->image()
            ->get(['imageable_type', 'url'])
            ->map(function ($image) {
                $dir = explode('\\', $image->imageable_type)[2];
                unset($image->imageable_type);
                return asset("storage/$dir") . '/' . $image->url;
            });
    }
}
