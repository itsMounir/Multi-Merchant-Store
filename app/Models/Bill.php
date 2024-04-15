<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

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
        'market_note',
        'rejection_reason',
    ];

    protected $appends = ['payement_method'];

    protected function getpayementMethodAttribute()
    {
        return $this->payementMethod()->get(['name']);
    }

<<<<<<< HEAD
    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class)->with('suppliers');
=======
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
>>>>>>> 6e088b9cd88d7c922c1dd7e07675380a4713f940
    }


    public function payementMethod(): BelongsTo
    {
        return $this->belongsTo(PayementMethod::class);
    }
<<<<<<< HEAD

=======
>>>>>>> 6e088b9cd88d7c922c1dd7e07675380a4713f940
    public function market()
    {
        return $this->belongsTo(Market::class);

    }

}
