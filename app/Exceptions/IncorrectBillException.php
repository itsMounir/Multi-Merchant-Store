<?php

namespace App\Exceptions;

use Exception;

class IncorrectBillException extends Exception
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;

        //$this->message = 'This account ' . $this->account . ' is inactive right now.';

        $this->code = 400;
    }
}
