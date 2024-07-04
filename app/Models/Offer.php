<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'image',
    ];

    protected $dates = ['created_at'];
    protected $appends = ['supplier_name'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // protected function image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn(string $value) => asset("storage/$value"),
    //     );
    // }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getSupplierNameAttribute()
    {
        return $this->supplier()->pluck('store_name')->first();
    }


}
