<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'name',
        'size',
        'size_of',
        'discription',
        'product_category_id',
    ];

    protected $dates = ['created_at', 'deleted_at'];

    //protected $appends = ['category'];

    // public function getCategoryAttribute() {
    //     return $this->category()->get(['name']);
    // }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot(
                'name',
                'price',
                'has_offer',
                'offer_price',
                'max_offer_quantity',
                'offer_expires_at',
            );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id');
    }
}
