<?php

namespace App\Traits;
use App\Models\{
    Supplier,
    Code
};
use Carbon\Carbon;
trait ExpierCode{
    public function isCodeExpired($code)
    {
        return $code->expires_at < Carbon::now();
    }
}
