<?php

namespace App\Exceptions;

use Exception;

class ProductNotExistForSupplierException extends Exception
{
    protected $productId;
    protected $supplierId;

    public function __construct($productId, $supplierId)
    {
        $this->productId = $productId;
        $this->supplierId = $supplierId;

        $this->message = 'The selected product with id (' . $this->productId . ') does not exist for the specified supplier with id (' . $this->supplierId . ').';

        $this->code = 400;
    }
}
