<?php

namespace App\Models;

use Illuminate\Support\Carbon;
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
        'goal_discount',
    ];

    protected $appends = ['payment_method', 'additional_price', 'waffarnalak', 'updatable'];

    protected $hidden = [
        'deleted_at'
    ];
        //protected $dates = ['created_at'];

    /*public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }*/


    protected $dates = ['created_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getAdditionalPriceAttribute()
    {
        if ($this->has_additional_cost) {
            return $this->total_price + $this->total_price * 1.5 / 100;
        } else {
            return $this->total_price;
        }
    }

    public function getWaffarnalakAttribute()
    {
        $total_discounted_price = 0.0;
        $waffarnalak = 0.0;
        $bill = static::find($this->id);
        $supplier_products = $bill->supplier->products->toArray();
        foreach ($bill->products as $product) {
            foreach ($supplier_products as $supplier_product) {
                if ($product['id'] == $supplier_product['id']) {

                    $price = $supplier_product['pivot']['price'];
                    $quantity = $product['pivot']['quantity'];
                    if ($supplier_product['pivot']['has_offer']) {
                        $total_discounted_price += min(
                            $supplier_product['pivot']['max_offer_quantity'],
                            $quantity
                        )
                            * ($price - $supplier_product['pivot']['offer_price']);
                    }
                }
            }
        }
        $waffarnalak = $total_discounted_price + $bill['goal_discount'];
        return $waffarnalak;
    }

    protected function getpaymentMethodAttribute()
    {
        return $this->paymentMethod()->pluck('name')->first();
    }

    public function getUpdatableAttribute()
    {
        return $this->isUpdatable();
    }

    public function getTotalPriceAfterDiscountAttribute()
    {
        return ($this->total_price - $this->goal_discount);
    }



    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity');
    }



    public function PaymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function scopeNewStatusCount($query, $supplier_id)
    {
        return $query->where('status', 'جديد')
            ->where('supplier_id', $supplier_id)
            ->count();
    }

    public function scopeStatus($query, $status = null)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopeOrderByInvoiceIdDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function isUpdatable()
    {
        $created_at_datetime = Carbon::parse($this->created_at);

        $expiration_time = $created_at_datetime->addMinutes(10);

        $current_time = Carbon::now();

        return $current_time->lt($expiration_time);
    }

    /**
     *  Scope to filter bills by supplier store name
     */
    public static function getBySupplierStoreName($name)
    {
        return self::with('supplier', 'market')->whereHas('supplier', function ($query) use ($name) {
            $query->where('store_name', 'like', '%' . $name . '%');
        })->orWhereHas('market', function ($query) use ($name) {
            $query->where('store_name', 'like', '%' . $name . '%');
        })->get();
    }

}
