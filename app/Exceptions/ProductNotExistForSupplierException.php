<?php

namespace App\Exceptions;

use Exception;

class ProductNotExistForSupplierException extends Exception
{
    protected $productId;
    protected $store_name;

    public function __construct($productId, $store_name)
    {
        $this->productId = $productId;
        $this->store_name = $store_name;

        $this->message = 'The selected product with id (' . $this->productId . ') does not exist for the specified supplier (' . $this->store_name . ').';

        $this->code = 400;
    }
}
