<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'payement_method_id',
        'status',
        'market_id',
        'supplier_id',
    ];

    protected $appends = ['payement_method'];

    protected function getpayementMethodAttribute()
    {
        return $this->payementMethod()->get(['name']);
    }

    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class);
    }

    public function payementMethod() : BelongsTo {
        return $this->belongsTo(PayementMethod::class);
    }
}
