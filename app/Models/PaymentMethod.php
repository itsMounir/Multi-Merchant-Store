<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];
}
