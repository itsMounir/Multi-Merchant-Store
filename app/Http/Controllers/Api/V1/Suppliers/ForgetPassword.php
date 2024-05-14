<?php

namespace App\Http\Controllers\Api\V1\Suppliers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Code,
    Supplier
};
use App\Traits\{
    createVerificationCode,
    Responses

};
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use Carbon\Carbon;

use Illuminate\Support\Facades\Notification;
use App\Notifications\verfication_code;
class ForgetPassword extends Controller{


use createVerificationCode,Responses;

    public function forgetPassword(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required',
            'name' => 'required',
        ]);

        $supplier = Supplier::where('phone_number', $validated['phone_number'])->firstOrFail();
        $verificationCode = $this->getOrCreateVerificationCode($validated['phone_number'], $validated['name']);

        Notification::route('mail', 'almowafratys09@gmail.com')
                    ->notify(new verfication_code($supplier, $verificationCode));

        return $this->sudResponse('.لقد تم إرسال طلبك إلى المشرف');
    }







}
