<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_price',
        'payement_method',
        'status',
        'market_id',
        'supplier_id',
    ];

    public function productSuppliers() : BelongsToMany {
        return $this->belongsToMany(ProductSupplier::class);
    }
}
