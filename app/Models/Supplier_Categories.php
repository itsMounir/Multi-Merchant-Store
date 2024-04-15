<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier_Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
    ];

    public function suppliers() : HasMany {
        return $this->hasMany(Supplier::class);
    }

}
