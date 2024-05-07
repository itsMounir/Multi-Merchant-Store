<?php

namespace App\Exceptions;

use Exception;

class InActiveAccountException extends Exception
{
    protected $account_name;

    public function __construct($account_name)
    {
        $this->account_name = $account_name;

        $this->message = '.' . 'هذا الحساب : ' . $this->account_name . ' غير نشط في الوقت الحالي';

        $this->code = 400;
    }
}
