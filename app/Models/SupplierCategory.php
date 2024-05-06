<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
    ];

    protected $dates = ['created_at'];

    /*protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];*/

    public function suppliers() : HasMany {
        return $this->hasMany(Supplier::class);
    }

}
