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
        'min_bill_price',
        'discount_price',
    ];


    protected $dates = ['created_at'];

   /* protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];*/

    protected $appends = ['supplier_store_name'];

    // images attribute
    // public function getImagesAttribute()
    // {
    //     return $this->images()
    //         ->get(['imageable_type', 'url'])
    //         ->map(function ($image) {
    //             $dir = explode('\\', $image->imageable_type)[2];
    //             unset ($image->imageable_type);
    //             return asset("public/$dir") . '/' . $image->url;
    //         });
    // }

    public function getSupplierStoreNameAttribute() {
        return $this->supplier()->pluck('store_name')->first();
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function markets(): BelongsToMany
    {
        return $this->belongsToMany(Market::class)->withTimestamps();
    }

    // morphs relation with images table
    // public function images()
    // {
    //     return $this->morphMany(Image::class, 'imageable');
    // }

}
