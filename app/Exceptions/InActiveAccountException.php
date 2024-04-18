<?php

namespace App\Exceptions;

use Exception;

class InActiveAccountException extends Exception
{
    protected $account;

    public function __construct($account)
    {
        $this->account = $account;

        $this->message = 'This account ' . $this->account . ' is inactive right now.';

        $this->code = 400;
    }
}
