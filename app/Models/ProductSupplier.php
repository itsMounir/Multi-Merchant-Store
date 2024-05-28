<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $table = 'product_supplier';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'supplier_id',
        'product_id',
        'price',
        'is_available',
        'max_selling_quantity',
        'has_offer',
        'offer_price',
        'max_offer_quantity',
        'offer_expires_at',
    ];


    protected $dates = ['created_at'];

    /*protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];*/

     public function bills() : BelongsToMany {
         return $this->belongsToMany(Bill::class);
     }



     public static function search_Product($supplierId, $productName, $is_available)
     {
         $productSuppliers = self::where('product_supplier.supplier_id', $supplierId)
                                  ->where('product_supplier.is_available', $is_available)
                                  ->join('products', 'product_supplier.product_id', '=', 'products.id')
                                  ->where('products.name', 'like', '%' . $productName . '%')
                                  ->select('product_supplier.id as ID', 'product_supplier.*', 'products.*')
                                  ->get();


         $productSuppliers->each(function ($productSupplier) {
             $product = Product::find($productSupplier->product_id);
             if ($product) {
                 $productSupplier->image = $product->getImageAttribute();
             }
             unset($productSupplier->id);
         });

         return $productSuppliers->toArray();
     }






}
