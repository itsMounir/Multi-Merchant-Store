<?php

namespace App\Http\Controllers\Api\V1\Markets\Auth;

use App\Enums\TokenAbility;
use App\Http\Requests\Api\V1\Markets\Auth\RegisterRequest;
use App\Models\{
    City,
    Market,
    MarketCategory,
    User
};
use App\Notifications\NewAccount;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Traits\FirebaseNotification;
use App\Traits\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class RegisterController extends Controller
{
    use FirebaseNotification;
    public function create(Request $request)
    {
        $cities = City::whereNull('parent_id')->with('childrens')->get();
        $categories = MarketCategory::get(['id', 'name']);
        return response()->json([
            'market_categories' => $categories,
            'cities' => $cities
        ]);
    }

    public function store(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $market = Market::create(
                array_merge(
                    $request->all(),
                    ['subscription_expires_at' => now()->addMonths(2)]
                )
            );

            $accessToken = $market->createToken(
                'access_token',
                [TokenAbility::ACCESS_API->value, 'role:market'],
                Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
            );

            $refreshToken = $market->createToken(
                'refresh_token',
                [TokenAbility::ISSUE_ACCESS_TOKEN->value],
                Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
            );

            $supervisor = User::role('supervisor')->get();
            DB::afterCommit(function () use ($supervisor, $market) {
                Notification::send($supervisor, new NewAccount($market, 'market'));
            });
            
            //Subsicribe To Market Topic
            $this->subscribeToTopic($market->deviceToken,'market');
            
            return response()->json([
                'message' => '.تم إنشاء الحساب بنجاح، يرجى انتظار تأكيده من الادمن',
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'market' => $market,
            ], 201);
        });
    }

}
