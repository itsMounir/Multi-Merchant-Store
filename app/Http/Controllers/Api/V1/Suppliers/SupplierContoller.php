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
    SupplierCategory,
    Market,
    User,
    City


};


use Illuminate\Support\Facades\{
    Auth,
    DB
};
use App\Notifications\{
    DistributionLocationUpdate,
    DiscountAdded

};
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Api\V1\Suppliers\{
    UpdateDistributionlocations,
    UpdateName,
    AddDiscountRequest
};

class SupplierContoller extends Controller
{

    use Responses;
    public function index(Request $request){
        $supplier = Auth::user();
        if (!$supplier) {
            return $this->sudResponse('Unauthorized', 401);
        }
        if ($request->has('search') && $request->search != '') {

            $data = Product::where('name', 'like', '%' . $request->search . '%')->get();
        } else {

            $data = Product::get();
        }
        return $this->indexOrShowResponse('products', $data);
    }


       public function categories_supplier(){
        $category = SupplierCategory::get();
        $cities = City::with('childrens.childrens')->whereNull('parent_id')->get();
        $data = [
            'categories' => $category,
            'cities' => $cities
        ];
        return $this->indexOrShowResponse('Body', $data);
    }
 public function Personal_Data(){
        $supplier = Auth::user();
        $supplier->load('city','supplierCategory');
        $supplierImages = $supplier->getImagesAttribute();
        $supplier->image = $supplierImages;
        $cities = City::all();
        $deliveryLocations = $supplier->distributionLocations->pluck('to_city_id')->toArray();
        foreach ($cities as $city) {
            $city->delivery_available = in_array($city->id, $deliveryLocations);
        }
        unset($supplier->distributionLocations);
        $data = [
            'supplier' => $supplier,
            'distribution_locations' => $cities
        ];

        return $this->indexOrShowResponse('body', $data);
    }

     /*   public function search(Request $request){

        return $this->indexOrShowResponse('body',$product=Product::where('name', 'like', '%' . $request->search . '%')->get());
    }*/


    public function edit_name(UpdateName $request){

        $supplier=Auth::user();
        $supplier->update($request->all());
        return $this->sudResponse('تم تعديل الاسم بنجاح');

    }

    public function updateDistributionLocations(UpdateDistributionlocations $request)
    {
        $supplier = Auth::user();
        $toSitesIds = $request->input('to_sites_id');
        $toSitesNames = City::whereIn('id', $toSitesIds)->pluck('name', 'id')->toArray();
        DB::afterCommit(function () use ($supplier, $toSitesNames) {
            $admins =  User::role('supervisor')->get();
            Notification::send($admins, new DistributionLocationUpdate($supplier, $toSitesNames));
        });
        return $this->sudResponse('تم إرسال طلب تعديل مناطق التوزيع إلى الأدمن للمراجعة.');
    }


    public function add_Discount(AddDiscountRequest $request)
    {
        $supplier = Auth::user();
        foreach ($request->input('discount') as $offerData) {
            $createdDiscount = $supplier->goals()->create($offerData);
        }
        $marketsToNotify = $supplier->getMarketsToNotify();
        Notification::send($marketsToNotify, new DiscountAdded($supplier));
        return $this->sudResponse('تم اضافة خصم بنجاح');
    }



    public function get_Discount(){
        $supplier=Auth::user();
        $data=$supplier->goals()->get()->with(['supplier']);
        return $this->sudResponse($data);
    }
}




