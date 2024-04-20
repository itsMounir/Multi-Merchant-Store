<?php

namespace App\Exceptions;

use Exception;

class InActiveAccountException extends Exception
{
    protected $account_name;

    public function __construct($account_name)
    {
        $this->account_name = $account_name;

        $this->message = 'This account ' . $this->account_name . ' is inactive right now.';

        $this->code = 400;
    }
}
