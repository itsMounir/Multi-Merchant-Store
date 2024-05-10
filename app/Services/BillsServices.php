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
use App\Models\User;
use App\Notifications\NewBillRequested;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
            'payment_method_id' => $bill['payment_method_id'],
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

        // update the users to recieve the notification !!!!!!!!!!!!
        $moderator = User::role('moderator')->get();

        // send a notification to the moderator with the new bill.
        DB::afterCommit(function () use ($moderator,$new_bill) {
            Notification::send($moderator, new NewBillRequested($new_bill->id));
        });

    }

    public function checkSupplierRequirements($supplier, $bill, $total_price)
    {
        if ($total_price < $supplier->min_bill_price) {
            throw new IncorrectBillException('.' . 'الرجاء استكمال السعر الأدنى : ' . $supplier->min_bill_price .' , للطلب لدى ' . $supplier->store_name );
        }

        $products_count = 0;
        foreach ($bill['products'] as $product) {
            $products_count += $product['quantity'];
        }

        if ($products_count < $supplier->min_selling_quantity) {
            throw new IncorrectBillException('.' . 'الرجاء استكمال العدد الأدنى للمنتجات : ' . $supplier->min_selling_quantity .' , للطلب لدى ' . $supplier->store_name );
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

                if ($product['id'] == $supplier_product['id'] && $supplier_product['pivot']['is_available']) {

                    $price = $supplier_product['pivot']['price'];
                    $quantity = $product['quantity']; // quantity requested
                    if ($quantity > $supplier_product['pivot']['max_selling_quantity']) {
                        throw new IncorrectBillException('.' . 'لقد تخطيت العدد الأقصى للطلب : ' . $supplier_product['pivot']['max_selling_quantity'] .' لدى ' . $supplier->store_name);
                    }

                    if ($supplier_product['pivot']['has_offer']) {
                        // calculate the total price of products taken in the offer
                        $total_price = min(
                            $supplier_product['pivot']['max_offer_quantity'],
                            $quantity
                        )
                            * $supplier_product['pivot']['offer_price'];

                        $quantity -= $supplier_product['pivot']['max_offer_quantity'];
                    }

                    if ($quantity > 0) {
                        $total_price += $price * $quantity; // in case requested quantity is more than offer quantity
                    }
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
            $goals = $supplier->goals()->orderByDesc('min_bill_price')->get();
            foreach ($goals as $goal) {
                if ($total_price >= $goal->min_bill_price) {
                    Auth::user()->goals()->attach($goal);
                    return $goal->discount_price;
                }
            }
        }
        return 0;
    }


    public function marketDiscount($market,$total_price){
        $supplier=Auth::user();
        if($supplier->goals()->count()>0){
            $goals = $supplier->goals()->orderByDesc('min_bill_price')->get();
            foreach ($goals as $goal) {
                if($total_price>=$goal->min_bill_price){
                    $market->goals()->attach($goal);
                    return $goal->discount_price;
                }
            }
        }
        return 0;
    }

    public function getProductsIds($bills): array
    {
        return collect($bills->with([
            'products' => function ($query) {
                $query->select('id'); // Select only the 'name' column
            }
        ])->get()->pluck('products')->toArray()[0])->pluck('id')->toArray();
    }
}
