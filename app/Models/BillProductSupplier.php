<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillProductSupplier extends Model
{
    use HasFactory;
    protected $table = 'bill_product_supplier';

    protected $fillable = [
        'bill_id',
        'product_supplier_id',
        'price',
        'quantity',
    ];
}
