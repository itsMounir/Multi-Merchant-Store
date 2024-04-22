<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    public function products() : HasMany 
    {
        return $this->hasMany(Product::class);
    }
}
