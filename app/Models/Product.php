<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'size',
        'discription',
        'category_id',
    ];


    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    //protected $appends = ['category'];

    // public function getCategoryAttribute() {
    //     return $this->category()->get(['name']);
    // }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot(
                'price',
                'has_offer',
                'offer_price',
                'max_offer_quantity',
                'offer_expires_at',
            );

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
