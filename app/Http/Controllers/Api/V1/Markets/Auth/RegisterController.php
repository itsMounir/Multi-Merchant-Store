<?php

namespace App\Http\Controllers\Api\V1\Markets\Auth;
use App\Http\Requests\Api\V1\Markets\Auth\RegisterRequest;
use App\Models\City;
use App\Models\Market;
use App\Models\MarketCategory;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use Images;
    public function create(Request $request) {
        $cities = City::whereNull('parent_id')->with('childrens')->get();
        $categories = MarketCategory::get(['id','name']);
        return response()->json([
            'market_categories' => $categories,
            'cities' => $cities
        ]);
    }

    public function store(RegisterRequest $request) {
        return DB::transaction(function () use ($request){

            $request_image = $request->image;
            $image = $this->setImagesName([$request_image])[0];

            $market = Market::create(array_merge(
                $request->all(),
                ['subscription_expires_at' => now()->addMonths(2)]
            ));

            $market->images()->create(['url' => $image]);
            $this->saveImages([$request_image], [$image], 'Markets');

            $token = $market->createToken('access_token', ['role:market'])->plainTextToken;

            return response()->json([
                'message' => 'Created Successfully please wait for admin confirmation',
                'access_token' => $token,
            ],201);
        });
    }

}
