<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionLocation extends Model
{
    use HasFactory;
    protected $table = 'distribution_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'to_city_id',
    ];


    protected $dates = ['created_at'];

    protected $appends = ['city_name'];
    
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function toCity()
    {
        return $this->belongsTo(City::class, 'to_city_id');
    }

    public function getCitynameAttribute(){
        return $this->toCity->name;

    }
}
