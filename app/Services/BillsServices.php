<?php

namespace App\Services;

use App\Exceptions\{
    InActiveAccountException,
    ProductNotExistForSupplierException,
    IncorrectBillException,
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
        $supplier = Supplier::findOrFail($bill['supplier_id']);

        if ($supplier->status != 'نشط') {
            throw new InActiveAccountException($supplier->store_name);
        }

        $total_price = $this->calculatePrice($bill, $supplier);

        $this->checkSupplierRequirements($supplier, $bill, $total_price);

        $total_price -= $this->supplierDiscount($supplier, $total_price);

        $new_bill = Bill::create([
            'total_price' => $total_price,
            'payement_method_id' => $bill['payement_method_id'],
            'supplier_id' => $supplier->id,
            'market_id' => $market->id,
            'has_additional_cost' => !Auth::user()->is_subscriped,
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

    public function checkSupplierRequirements($supplier, $bill, $total_price)
    {

        if ($total_price < $supplier->min_bill_price) {
            throw new IncorrectBillException('Total price : ' . $total_price . ' of this bill is less than ' . $supplier->store_name . ' store minimum price of bill : ' . $supplier->min_bill_price . ' .');
        }

        $products_count = 0;
        foreach ($bill['products'] as $product) {
            $products_count += $product['quantity'];
        }

        if ($products_count < $supplier->min_selling_quantity) {
            throw new IncorrectBillException('Products count : ' . $products_count . ' of this bill is less than ' . $supplier->store_name . ' store minimum number of products : ' . $supplier->min_selling_quantity . ' .');
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
                    $price = $supplier_product['pivot']['price'];

                    if ($supplier_product['pivot']['has_offer']) {

                        if ($product['quantity'] > $supplier_product['pivot']['max_offer_quantity']) {
                            throw new IncorrectBillException('Products number of this bill : ' . $product['quantity'] . ' is more than the max offer quantity : ' . $supplier_product['pivot']['max_offer_quantity'] . ' .');
                        }
                        $price = $supplier_product['pivot']['offer_price'];
                    }

                    $total_price += $price * $product['quantity'];
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
    public function supplierDiscount($supplier, $total_price): float
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
