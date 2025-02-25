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
use Illuminate\Http\JsonResponse;


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
use App\Traits\FirebaseNotification;

class SupplierContoller extends Controller
{

    use Responses, FirebaseNotification;
    public function index(Request $request)
    {
        $supplier = Auth::user();
        if (!$supplier) {
            return $this->sudResponse('Unauthorized', 401);
        }

        $query = Product::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $data = $query->paginate(10);

        return response()->json([
            'products' => $data
        ]);
    }


    public function categories_supplier()
    {
        $category = SupplierCategory::get();
        $cities = City::with('childrens.childrens')->whereNull('parent_id')->get();
        $data = [
            'categories' => $category,
            'cities' => $cities
        ];
        return $this->indexOrShowResponse('Body', $data);
    }



    public function Personal_Data()
    {
        $supplier = Auth::user();
        $supplier->load('city', 'supplierCategory');
        $supplierImages = $supplier->getImagesAttribute();
        $supplier->images= [$supplierImages];
        $cities = City::all();
        $deliveryLocations = $supplier->distributionLocations;

        foreach ($cities as $city) {
            $city->delivery_available = false;
            foreach ($deliveryLocations as $location) {
                if ($location->to_city_id == $city->id) {
                    $city->delivery_available = true;
                    $city->min_bill_price = $location->min_bill_price;
                    break;
                }
            }
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


    public function edit_name(UpdateName $request)
    {

        $supplier = Auth::user();
        $supplier->update($request->all());
        return $this->sudResponse('تم تعديل الاسم بنجاح');
    }

    public function updateDistributionLocations(UpdateDistributionlocations $request)
    {
        $supplier = Auth::user();
        $toSitesData = $request->input('to_sites_id');

        $toSitesIds = array_column($toSitesData, 'id');

        $toSites = City::whereIn('id', $toSitesIds)->get()->keyBy('id');

        $toSitesNamesWithPrices = [];
        foreach ($toSitesData as $siteData) {
            $siteId = $siteData['id'];
            $siteName = $toSites[$siteId]->name;
            $minBillPrice = $siteData['min_bill_price'];
            $toSitesNamesWithPrices[$siteName] = $minBillPrice;
        }

        DB::afterCommit(function () use ($supplier, $toSitesNamesWithPrices) {
            $admins = User::role('supervisor')->get();
            Notification::send($admins, new DistributionLocationUpdate($supplier, $toSitesNamesWithPrices));
        });


        return $this->sudResponse('تم إرسال طلب تعديل مناطق التوزيع إلى الأدمن للمراجعة.');
    }

    public function add_Discount(AddDiscountRequest $request)
    {
        // $notification=new MobileNotificationServices;
        $supplier = Auth::user();

        foreach ($request->input('discount') as $offerData) {
            $createdDiscount = $supplier->goals()->create($offerData);
        }
        $marketsToNotify = $supplier->getMarketsToNotify();


        foreach ($marketsToNotify as $market) {
            Notification::send($market, new DiscountAdded($supplier->append('category_name','city_name')));
            $this->sendNotification($market->deviceToken,"خصم جديد","تم اضافة خصم من قبل ". $supplier->store_name . ".");


        }
        return $this->sudResponse('تم اضافة خصم بنجاح');
    }


    public function get_Discount()
    {
        $supplier = Auth::user();
        $data = $supplier->goals()->get()->with(['supplier']);
        return $this->sudResponse($data);
    }


    public function getDeviceToken(Request $request)
    {
        $data = $request->validate(['device_token' => 'required']);
        $supplier = Auth::user();
        $supplier->update(['deviceToken' => $data['device_token']]);
        return $this->sudResponse('تم تحديث التوكن بنجاح');
    }
}
