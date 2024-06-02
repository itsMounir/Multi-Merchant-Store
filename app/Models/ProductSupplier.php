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
         $productSuppliers = self::where('supplier_id', $supplierId)
                                  ->where('is_available', $is_available)
                                  ->join('products', 'product_supplier.product_id', '=', 'products.id')
                                  ->where('products.name', 'like', '%' . $productName . '%')
                                  ->select('product_supplier.id as ID', 'product_supplier.*','products.*')
                                  ->get();

         $response = $productSuppliers->map(function ($productSupplier) {
             $product = Product::find($productSupplier->product_id);
             $productCategory = ProductCategory::find($productSupplier->product_category_id);
             return [
                 'id' => $productSupplier->product_id,
                 'product_category_id' => $productSupplier->product_category_id,
                 'name' => $product->name,
                 'discription' => $product->discription,
                 'size' => $product->size,
                 'size_of' => $product->size_of,
                 'created_at' => $product->created_at,
                 'updated_at' => $product->updated_at,
                 'image' => $product->getImageAttribute(),
                 'product_category' =>$productCategory->name ,
                 'pivot' => [
                     'supplier_id' => $productSupplier->supplier_id,
                     'product_id' => $productSupplier->product_id,
                     'id'=>$productSupplier->ID,
                     'price' => $productSupplier->price,
                     'has_offer' => $productSupplier->has_offer,
                     'offer_price' => $productSupplier->offer_price,
                     'max_offer_quantity' => $productSupplier->max_offer_quantity,
                     'offer_expires_at' => $productSupplier->offer_expires_at,
                     'max_selling_quantity' => $productSupplier->max_selling_quantity,
                     'is_available' => $productSupplier->is_available
                 ]
             ];
         });

         return  $response->toArray();
     }






}
