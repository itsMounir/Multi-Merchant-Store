<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'size',
        'discription',
        'category_id',
    ];

    public function suppliers() : BelongsToMany {
        return $this->belongsToMany(Supplier::class,'product_suppliers');
    }

    public function category() : BelongsTo {
        return $this->belongsTo(ProductCategory::class);
    }
}
