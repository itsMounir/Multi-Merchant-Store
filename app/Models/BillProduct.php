<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillProduct extends Model
{
    use HasFactory;
    protected $table = 'bill_product';

    protected $fillable = [
        'bill_id',
        'product_id',
        'buying_price',
        'quantity',
        'buying_price',
        'max_selling_quantity',
        'has_offer',
        'offer_buying_price',
        'max_offer_quantity'
    ];


    protected $dates = ['created_at'];

   /* protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];*/
}


