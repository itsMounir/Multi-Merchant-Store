<?php

namespace App\Http\Controllers\Api\V1\Users\Auth;

use App\Http\Requests\Api\V1\users\Auth\CreateAccountRequest;
use App\Notifications\verfication_code;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateAccountController extends Controller
{
    public function create(CreateAccountRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $user = User::create($request->all());
            $user->assignRole('employee');
            //$user->notify(new  verfication_code($verfication_code));
            return response()->json(['message' => 'Account has been created successfully ', 'User' => $user], 201);
        });
    }
}
