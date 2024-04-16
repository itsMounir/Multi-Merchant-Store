<?php

namespace App\Exceptions;

use Exception;

class InActiveSupplierException extends Exception
{
    protected $supplier;

    public function __construct(\App\Models\Supplier $supplier)
    {
        $this->supplier = $supplier;

        $this->message = 'This supplier ' . $this->supplier->store_name . ' account is inactive right now.';

        $this->code = 400;
    }
}
