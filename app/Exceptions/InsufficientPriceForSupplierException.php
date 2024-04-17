<?php

namespace App\Exceptions;

use Exception;

class InsufficientPriceForSupplierException extends Exception
{
    protected $total_price;

    protected $supplier;



    public function __construct($total_price,\App\Models\Supplier $supplier)
    {
        $this->total_price = $total_price;
        $this->supplier = $supplier;

        $this->message = 'Total price : ' . $this->total_price . ' of this bill is less than ' . $this->supplier->store_name . ' store minimum price of bill : ' . $this->supplier->min_bill_price .' .';

        $this->code = 400;
    }
}
