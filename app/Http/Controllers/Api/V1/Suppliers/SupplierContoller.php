<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Responses;
use App\Models\Supplier;
use App\Models\ProductSupplier;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;

class SupplierContoller extends Controller
{

    use Responses;
    public function index(){
        //عرض المنتجات في المستودع الاساسي
        $supplier=Auth::user();
        if(!$supplier){
            return $this->sudResponse('Unauthorized',401);
        }
        $data=ProductCategory::with('products')->get();
        return $this->indexOrShowResponse('message',$data);


    }
}
