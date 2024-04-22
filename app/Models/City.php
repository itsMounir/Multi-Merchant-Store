<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(City::class, 'parent_id');
    }

    public function distributionLocationsFrom()
    {
        return $this->hasMany(DistributionLocation::class, 'from_city_id');
    }

    public function distributionLocationsTo()
    {
        return $this->hasMany(DistributionLocation::class, 'to_city_id');
    }
}
