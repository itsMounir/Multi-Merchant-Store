<?php

namespace App\Exceptions;

use Exception;

class ProductNotExistForSupplierException extends Exception
{
    public $product;
    public $supplier;

    public function __construct($product, $supplier)
    {
        $this->product = $product;
        $this->supplier = $supplier;

        $this->message = 'عفوا ! اصبح ' . $product->name.' غير متاح الآن عند ' . $supplier->store_name .' برجاء إلغائه من الفاتورة لاستكمال الفاتورة بنجاح';
        $this->code = 400;
    }
}
