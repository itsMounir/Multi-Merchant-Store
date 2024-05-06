<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';

    protected $dates = ['created_at'];


    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    public function bills() : HasMany {
        return $this->hasMany(Bill::class);
    }



}
