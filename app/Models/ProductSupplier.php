<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $table = 'product_suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'product_id',
        'price',
        'price_after_sales',
        'discount_by_code',
    ];

    public function bills() : BelongsToMany {
        return $this->belongsToMany(Bill::class);
    }
}
