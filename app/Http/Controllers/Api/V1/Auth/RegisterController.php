<?php

namespace App\Http\Controllers\Api\V1\Auth;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create(RegisterRequest $request) {
        return DB::transaction(function () use ($request){

            $verfication_code=mt_rand(100000,999999);
            $request['code'] = $verfication_code;
            $user = User::create($request->all());
            //Auth::login($user);

            $token = $user->createToken('access_token')->plainTextToken;
            $user->notify(new  verfication_code($verfication_code));
            return response()->json([
                'message' => 'Code Sent',
                'access_token' => $token
            ],201);
        });
    }
}
