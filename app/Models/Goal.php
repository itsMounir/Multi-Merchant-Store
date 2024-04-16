<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'starting_date',
        'expiring_date',
        'min_price',
        'discount_price',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function markets(): BelongsToMany
    {
        return $this->belongsToMany(Market::class)->withTimestamps();
    }
}
