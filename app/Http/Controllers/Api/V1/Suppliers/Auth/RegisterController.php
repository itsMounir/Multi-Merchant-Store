<?php

namespace App\Http\Controllers\Api\V1\Suppliers\Auth;

use App\Enums\TokenAbility;
use App\Models\{
    Supplier,
    DistributionLocation
};
use App\Http\Requests\Api\V1\Suppliers\{
    RegisterSupplier
};
use App\Notifications\NewAccount;
use App\Traits\Images;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Services\MobileNotificationServices;

class RegisterController extends Controller
{
    use Images;
    public function create(RegisterSupplier $request)
    {
        return DB::transaction(function () use ($request) {
            $supplier = Supplier::create($request->all());
            $toCityIds = $request->input('to_sites');
            if (!empty($toCityIds)) {
                foreach ($toCityIds as $toCityId) {
                    DistributionLocation::create([
                        'supplier_id' => $supplier->id,
                        'to_city_id' => $toCityId,
                    ]);
                }
            }
            if ($request->hasFile('image')) {
                $request_image = $request->file('image');
                $image_name = $this->setImagesName([$request_image])[0];

                $supplier->image()->create(['url' => $image_name]);
                $this->saveImages([$request_image], [$image_name], 'public/Supplier');
            }

            $supervisor = User::role('supervisor')->get();
            DB::afterCommit(function () use ($supervisor, $supplier) {
                Notification::send($supervisor, new NewAccount($supplier, 'supplier'));
            });

            $accessToken = $supplier->createToken(
                'access_token',
                [TokenAbility::ACCESS_API->value, 'role:supplier'],
                Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
            );

            $refreshToken = $supplier->createToken(
                'refresh_token',
                [TokenAbility::ISSUE_ACCESS_TOKEN->value],
                Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
            );


            //$notification = new MobileNotificationServices;
           // $notification->subscribeToTopic($supplier->deviceToken,'supplier');

            return response()->json([
                'message' => '.تم إنشاء الحساب بنجاح، يرجى انتظار التأكيد من الادمن',
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'supplier' => $supplier,
            ], 200);
        });
    }
}
