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
        'recieved_price',
        'payement_method_id',
        'status',
        'market_id',
        'supplier_id',
        'market_note',
        'rejection_reason',
        'has_additional_cost',
        'delivery_duration',
    ];

    protected $appends = ['payement_method','additional_price'];

    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    public function getAdditionalPriceAttribute()
    {
        if ($this->has_additional_cost) {
            return $this->total_price+$this->total_price*1.5/100;
        } else {
            return $this->total_price;
        }
    }

    protected function getpayementMethodAttribute()
    {
        //return $this->PaymentMethod()->get(['name']);
    }


    public function products() : BelongsToMany {
        return $this->belongsToMany(Product::class);

    }


    public function PaymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }



    public function market()
    {
        return $this->belongsTo(Market::class);

    }

    public function scopeStatus($query, $status)
    {
        if(!empty($status)){
        return $query->where('status', $status);
        }
        return $query;
    }

    }
