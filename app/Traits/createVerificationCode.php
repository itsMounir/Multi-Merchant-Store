<?php

namespace App\Traits;
use App\Models\{
    Supplier,
    Code
};
use Carbon\Carbon;
trait createVerificationCode
{

    protected function getOrCreateVerificationCode($phone, $name)
    {
        $currentCode = Code::where('phone', $phone)
                           ->latest()
                           ->first();

        if ($currentCode && $currentCode->created_at > now()->subMinutes(60)) {
            return $currentCode->verification_code;
        }

        $verificationCode = mt_rand(100000, 999999);
        Code::create([
            'phone' => $phone,
            'name' => $name,
            'verification_code' => $verificationCode,
            'expires_at' => Carbon::now()->addMinutes(30),

        ]);

        return $verificationCode;
    }

}
