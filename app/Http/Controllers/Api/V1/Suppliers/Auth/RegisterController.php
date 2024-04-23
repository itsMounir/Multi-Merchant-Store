<?php

namespace App\Http\Controllers\Api\V1\Suppliers\Auth;
use App\Models\{
    Supplier,
    DistributionLocation

};
use App\Http\Requests\Api\V1\Suppliers\{
    RegisterSupplier,
    DistributionLocationRequest
};
use App\Traits\Images;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use Images;
    public function create(RegisterSupplier $request1, DistributionLocationRequest $request2) {
        return DB::transaction(function () use ($request1, $request2) {
            $supplier = Supplier::create($request1->all());
            $toCityIds = $request2->input('to_sites');
            if (!empty($toCityIds)) {
                foreach ($toCityIds as $toCityId) {
                    DistributionLocation::create([
                        'supplier_id' => $supplier->id,
                        'to_city_id' => $toCityId,
                    ]);
                }
            }
            if ($request1->hasFile('image')) {
                $request_image = $request1->file('image');
                $image_name = $this->setImagesName([$request_image])[0];

                $supplier->image()->create(['url' => $image_name]);
                $this->saveImages([$request_image], [$image_name], 'suppliers');
            }
            $token = $supplier->createToken('access_token', ['role:supplier'])->plainTextToken;
            return response()->json([
                'message' => 'Created Successfully please wait for admin confirmation',
                'access_token' => $token,
            ], 200);
        });
    }




}
