<?php

namespace App\Http\Controllers\Api\V1\Suppliers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Code,
    Supplier
};

use App\Traits\{
    Responses,
    ExpierCode

};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Auth,
    DB
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verfication_code;

class ResetPassword extends Controller{

    use ExpierCode,Responses;
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required',
        ]);

        $code = Code::where('verification_code', $request->verification_code)
                    ->first();

        if (!$code) {
            return $this->sudResponse('.الرمز غير صحيح', 400);
        }

        if ($this->isCodeExpired($code)) {
            $code->delete();
            return $this->sudResponse('.رمز التحقق لم يعد صالحا', 400);
        }

        return $this->sudResponse('تم تأكيد الرمز');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'password' => 'required',
        ]);

        $supplier = Supplier::where('phone_number', $request->phone_number)->first();

        $supplier->password = bcrypt($request->password);
        $supplier->save();

        Code::where('phone', $request->phone_number)->delete();

        return $this->sudResponse('.تم إعادة تعيين كلمة المرور بنجاح');
    }



}
