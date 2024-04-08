<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Market extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone_number',
        'password',
        'city',
        'street',
        'category_id',
        'presentator_code',
    ];

    public function image() : MorphOne {
        return $this->morphOne(Image::class,'imageable');
    }

    public function bills() : HasMany {
        return $this->hasMany(Bill::class);
    }

    public function category() : BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
