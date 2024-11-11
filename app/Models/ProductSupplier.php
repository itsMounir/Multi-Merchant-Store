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
        'quantity',
    ];


    protected $dates = ['created_at'];

    /*protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];*/

    public function bills(): BelongsToMany
    {
        return $this->belongsToMany(Bill::class);
    }

    public function getSupplierStoreNameAttribute()
    {
        return $this->supplier()->pluck('store_name')->first();
    }


    public static function search_Product($supplierId, $productName, $is_available)
    {
        $productSuppliers = self::where('supplier_id', $supplierId)
            ->where('is_available', $is_available)
            ->join('products', 'product_supplier.product_id', '=', 'products.id')
            ->where('products.name', 'like', '%' . $productName . '%')
            ->select('product_supplier.id as ID', 'product_supplier.*', 'products.*')
            ->paginate(10);

        $response = $productSuppliers->getCollection()->map(function ($productSupplier) {
            $product = Product::find($productSupplier->product_id);
            $productCategory = ProductCategory::find($productSupplier->product_category_id);

            if ($product) {
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
                    'product_category' => $productCategory ? $productCategory->name : null,
                    'pivot' => [
                        'supplier_id' => $productSupplier->supplier_id,
                        'product_id' => $productSupplier->product_id,
                        'id' => $productSupplier->ID,
                        'price' => $productSupplier->price,
                        'quantity' => $productSupplier->quantity,
                        'has_offer' => $productSupplier->has_offer,
                        'offer_price' => $productSupplier->offer_price,
                        'max_offer_quantity' => $productSupplier->max_offer_quantity,
                        'offer_expires_at' => $productSupplier->offer_expires_at,
                        'max_selling_quantity' => $productSupplier->max_selling_quantity,
                        'is_available' => $productSupplier->is_available,
                    ],
                ];
            }
        })->filter();


        $links = [];
        $totalPages = $productSuppliers->lastPage();

        for ($i = 1; $i <= $totalPages; $i++) {
            $links[] = [
                'url' => $productSuppliers->url($i),
                'label' => (string) $i,
                'active' => $i === $productSuppliers->currentPage(),
            ];
        }

        return [
            'current_page' => $productSuppliers->currentPage(),
            'data' => $response->values()->toArray(),
            'first_page_url' => $productSuppliers->url(1),
            'from' => $productSuppliers->firstItem(),
            'last_page' => $productSuppliers->lastPage(),
            'last_page_url' => $productSuppliers->url($productSuppliers->lastPage()),
            'links' => [
                [
                    'url' => $productSuppliers->previousPageUrl(),
                    'label' => '&laquo; Previous',
                    'active' => false,
                ],
                ...$links,
                [
                    'url' => $productSuppliers->nextPageUrl(),
                    'label' => 'Next &raquo;',
                    'active' => false,
                ]
            ],
            'next_page_url' => $productSuppliers->nextPageUrl(),
            'prev_page_url' => $productSuppliers->previousPageUrl(),
            'path' => $productSuppliers->path(),
            'per_page' => $productSuppliers->perPage(),
            'to' => $productSuppliers->currentPage() * $productSuppliers->perPage(),
            'total' => $productSuppliers->total(),
        ];


    }

}
