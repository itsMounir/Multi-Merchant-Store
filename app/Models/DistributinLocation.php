<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributinLocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'from_site',
        'to_site',
    ];


    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    public function supplier() : BelongsTo {
        return $this->belongsTo(Supplier::class);
    }
}
