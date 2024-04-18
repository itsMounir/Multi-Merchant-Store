<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $table = 'product_supplier';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'product_id',
        'price',
        'is_available',
        'max_selling_quantity',
        'has_offer',
        'offer_price',
        'max_offer_quantity',
        'offer_expires_at',
    ];

    // public function bills() : BelongsToMany {
    //     return $this->belongsToMany(Bill::class);
    // }



}
