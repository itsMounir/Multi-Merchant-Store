<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Bill extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_price',
        'payement_method_id',
        'status',
        'market_id',
        'supplier_id',
        'discount_code'
    ];

    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class);
    }
    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
