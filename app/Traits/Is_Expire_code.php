<?php

namespace App\Traits;
use Carbon\Carbon;
trait Is_Expire_code{

public function isCodeExpired($user){

    $codeCreationTime = $user->created_at;
    $currentTime = Carbon::now();

    return $currentTime->diffInMinutes($codeCreationTime) > 5;
    }
}
