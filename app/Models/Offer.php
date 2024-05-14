<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'image',
    ];

    protected $dates = ['created_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user's first name.
     */
  /*  protected function image(): Attribute
   * {
   *     return Attribute::make(
  *          get: fn(string $value) => asset("storage/$value"),
   *     );
    }*/

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
