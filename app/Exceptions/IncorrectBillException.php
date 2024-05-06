<?php

namespace App\Exceptions;

use Exception;

class IncorrectBillException extends Exception
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;

        $this->code = 400;
    }
}
