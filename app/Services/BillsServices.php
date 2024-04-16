<?php

namespace App\Services;

use App\Exceptions\{
    InActiveSupplierException,
    InsufficientPriceForSupplierException,
    ProductNotExistForSupplierException
};
use App\Models\{
    Bill,
    Supplier
};
use Illuminate\Support\Facades\Auth;

class BillsServices
{
    /**
     * all about bill's calculations and creation.
     */
    public function process($bill, $market)
    {
        $supplier = Supplier::find($bill['supplier_id']);

        if ($supplier->status != 'نشط') {
            throw new InActiveSupplierException($supplier);
        }

        $total_price = $this->calculatePrice($bill, $supplier);

        if ($total_price < $supplier->min_bill_price) {
            throw new InsufficientPriceForSupplierException($total_price,$supplier);
        }

        $total_price -= $this->discounts($supplier, $total_price);

        $new_bill = Bill::create([
            'total_price' => $total_price,
            'payement_method_id' => $bill['payement_method_id'],
            'supplier_id' => $supplier->id,
            'market_id' => $market->id,
            'has_additional_cost' => ! Auth::user()->is_subscriped,
            'market_note' => $bill['market_note'],
        ]);

        foreach ($bill['products'] as $product) {
            $new_bill->products()->syncWithoutDetaching([
                $product['id'] => [
                    'quantity' => $product['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

    }

    /**
     * calculate the price for the specified bill
     */
    public function calculatePrice($bill, $supplier): float
    {

        $total_price = 0.0;
        $supplier_products = $supplier->products->toArray();

        foreach ($bill['products'] as $product) {
            $exist = false;
            foreach ($supplier_products as $supplier_product) {
                if ($product['id'] == $supplier_product['id']) {
                    $total_price += $supplier_product['pivot']['price'] * $product['quantity'];
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                throw new ProductNotExistForSupplierException($product['id'], $supplier->store_name);
            }
        }
        return $total_price;

    }

    /**
     * return discount value earned by achieving supplier's goals .
     */
    public function discounts($supplier, $total_price): float
    {
        if ($supplier->goals()->count() > 0) {
            $goals = $supplier->goals()->orderByDesc('min_price')->get();
            foreach ($goals as $goal) {
                if ($total_price >= $goal->min_price) {
                    Auth::user()->goals()->attach($goal);
                    return $goal->discount_price;
                }
            }
        }
        return 0;
    }
}
