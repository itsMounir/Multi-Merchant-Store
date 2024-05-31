<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'name',
        'parent_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function parent()
    {
        return $this->belongsTo(City::class, 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(City::class, 'parent_id');
    }

    public function distributionLocationsTo(): HasMany
    {
        return $this->hasMany(DistributionLocation::class, 'to_city_id');
    }

    public function markets(): HasMany
    {
        return $this->hasMany(Market::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }
}
