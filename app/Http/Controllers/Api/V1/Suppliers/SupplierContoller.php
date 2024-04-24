<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Responses;
use App\Models\{
    Supplier,
    Product,
    ProductSupplier,
    ProductCategory,
    DistributionLocation,


};


use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Http\Requests\Api\V1\Suppliers\{
    UpdateDistributionlocations,
    UpdateName,
    AddDiscountRequest
};

class SupplierContoller extends Controller
{

    use Responses;
    public function index(){
        //عرض المنتجات في المستودع الاساسي
        $supplier=Auth::user();
        if(!$supplier){
            return $this->sudResponse('Unauthorized',401);
        }
        $data=ProductCategory::with(['products'.'Images'])->get();
        return $this->indexOrShowResponse('message',$data);


    }

    public function Personal_Data(){
        $supplier=Auth::user();
        return $this->indexOrShowResponse('message',$supplier);

    }

    public function edit_name(UpdateName $request){

        $supplier=Auth::user();
        $supplier->update($request->all());
        return $this->sudResponse('Name has been update');

    }

    public function updateDistributionLocations(UpdateDistributionlocations $request)
    {
        $supplier =Auth::user();
        $toSitesIds = $request->input('to_sites_id');
        DB::transaction(function () use ($supplier, $toSitesIds) {
            $supplier->distributionLocations()->delete();
            foreach ($toSitesIds as $toCityId) {
                DistributionLocation::create([
                    'supplier_id' => $supplier->id,
                    'to_city_id' => $toCityId,
                ]);
            }
        });

        return $this->sudResponse('Distribution locations updated successfully');
    }


    public function add_Discount(AddDiscountRequest $request)
    {

        $supplier = Auth::user();
        foreach ($request->input('discount') as $offerData) {
            $supplier->goals()->create($offerData);
        }
        return $this->sudResponse('Discount added successfully');
    }
}




