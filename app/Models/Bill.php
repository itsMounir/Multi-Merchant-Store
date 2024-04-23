<?php

namespace App\Models;

use Carbon\Carbon;
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
        'payment_method_id',
        'status',
        'market_id',
        'supplier_id',
        'market_note',
        'rejection_reason',
        'has_additional_cost',
        'delivery_duration',
    ];

    protected $appends = ['created_from','payment_method','additional_price'];

    protected $dates = ['created_at'];

    // created from attribute
    public function getCreatedFromAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function isUpdatable()
    {
        $created_at_datetime = Carbon::parse($this->created_at);

        $expiration_time = $created_at_datetime->addMinutes(10);

        $current_time = Carbon::now();

        return $current_time->lt($expiration_time);
    }

    public function getAdditionalPriceAttribute()
    {
        if ($this->has_additional_cost) {
            return $this->total_price + $this->total_price*1.5/100;
        } else {
            return $this->total_price;
        }
    }

    protected function getpaymentMethodAttribute()
    {

        return $this->paymentMethod()->get(['name']);
    }


    public function products() : BelongsToMany {

        return $this->belongsToMany(Product::class);
    }



    public function PaymentMethod(): BelongsTo

    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function scopeNewStatusCount($query)
    {
        return $query->where('status', 'جديد')->count();
    }

    public function scopeStatus($query, $status = null)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }


}
