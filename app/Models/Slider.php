<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'image',
    ];

    protected $dates = ['created_at'];

    public function supplier()
    {

        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
