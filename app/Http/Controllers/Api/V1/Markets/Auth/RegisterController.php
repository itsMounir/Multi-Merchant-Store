<?php

namespace App\Http\Controllers\Api\V1\Markets\Auth;
use App\Models\Market;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create(Request $request) {
        return DB::transaction(function () use ($request){

            // $verfication_code=mt_rand(100000,999999);
            // $request['code'] = $verfication_code;
            $market = Market::create($request->all());
            //Auth::login($market);

            $token = $market->createToken('access_token', ['role:market'])->plainTextToken;
           // $user->notify(new  verfication_code($verfication_code));
            return response()->json([
                'message' => 'Created Successfully please wait for admin confirmation',
                'access_token' => $token,
            ],201);
        });
    }
}
