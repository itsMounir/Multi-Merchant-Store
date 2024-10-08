<?php

namespace App\Services;

use App\Exceptions\{
    InActiveAccountException,
    ProductNotExistForSupplierException,
    IncorrectBillException,
};
use App\Models\{
    Bill,
    Supplier,
    Product,
    BillProduct
};
use App\Models\Coupon;
use App\Models\CouponBill;
use App\Models\User;
use App\Notifications\NewBillRequested;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class BillsServices
{
    /**
     * all about bill's calculations and creation.
     */
    public function process($bill, $market)
    {
        $supplier = Supplier::findOrFail($bill['supplier_id'])->append('min_bill_price');

        if ($supplier->status != 'نشط') {
            throw new InActiveAccountException($supplier->store_name);
        }

        $total_price = $this->calculatePrice($bill, $supplier);

        $this->checkSupplierRequirements($supplier, $bill, $total_price);

        $supplier_discount = $this->supplierDiscount($supplier, $total_price);

        if (!is_null($bill['market_note'])) {
            $new_bill = Bill::create([
                'total_price' => $total_price,
                'goal_discount' => $supplier_discount,
                'payment_method_id' => $bill['payment_method_id'],
                'supplier_id' => $supplier->id,
                'market_id' => $market->id,
                'has_additional_cost' => !Auth::user()->is_subscriped,
                'market_note' => $bill['market_note'],
            ]);

        } else {
            $new_bill = Bill::create([
                'total_price' => $total_price,
                'goal_discount' => $supplier_discount,
                'payment_method_id' => $bill['payment_method_id'],
                'supplier_id' => $supplier->id,
                'market_id' => $market->id,
                'has_additional_cost' => !Auth::user()->is_subscriped,
            ]);

        }

        foreach ($bill['products'] as $product) {
            $new_bill->products()->syncWithoutDetaching([
                $product['id'] => [
                    'quantity' => $product['quantity'],
                    'buying_price' => $product['buying_price'],
                    'max_selling_quantity' => $product['max_selling_quantity'],
                    'has_offer' => $product['has_offer'],
                    'offer_buying_price' => $product['offer_buying_price'],
                    'max_offer_quantity' => $product['max_offer_quantity'],
                    'offer_expires_at' => $product['offer_expires_at'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if (array_key_exists('coupon_code', $bill)) {
            $this->checkCouponDiscount($new_bill, $bill['coupon_code']);
        }

        // update the users to recieve the notification !!!!!!!!!!!!
        $moderator = User::role('moderator')->get();

        // send a notification to the moderator with the new bill.
        DB::afterCommit(function () use ($moderator, $new_bill, $supplier, $market) {
            Notification::send($moderator, new NewBillRequested($new_bill->id));


            // $notification = new MobileNotificationServices();
            // $message = 'you have a new bill requested from ' . $market->store_name;
            // $notification->sendNotification($supplier->device_token, 'new bill', $message);

        });
        if ($supplier_discount != 0) {
            return 'لقد استفدت من الخصم لدى : ' . $supplier->store_name;
        } else {
            return '';
        }
    }

    public function checkSupplierRequirements($supplier, $bill, $total_price)
    {
        if ($total_price < $supplier->min_bill_price) {
            throw new IncorrectBillException('.' . 'الرجاء استكمال السعر الأدنى : ' . $supplier->min_bill_price . ' , للطلب لدى ' . $supplier->store_name);
        }

        if (count($bill['products']) < $supplier->min_selling_quantity) {
            throw new IncorrectBillException('.' . 'الرجاء استكمال العدد الأدنى للمنتجات : ' . $supplier->min_selling_quantity . ' , للطلب لدى ' . $supplier->store_name);
        }
    }

    /**
     * calculate the price for the specified bill
     */
    public function calculatePrice(&$bill, $supplier): float
    {
        $total_price = 0.0;
        $supplier_products = $supplier->products->toArray();
        $i = 0;
        foreach ($bill['products'] as $product) {
            $exist = false;
            foreach ($supplier_products as $supplier_product) {

                if ($product['id'] == $supplier_product['id'] && $supplier_product['pivot']['is_available']) {
                    $price = $supplier_product['pivot']['price'];
                    $bill['products'][$i]['buying_price'] = $price;
                    $bill['products'][$i]['max_selling_quantity'] = $supplier_product['pivot']['max_selling_quantity'];
                    $bill['products'][$i]['has_offer'] = $supplier_product['pivot']['has_offer'];
                    $bill['products'][$i]['offer_buying_price'] = $supplier_product['pivot']['offer_price'];
                    $bill['products'][$i]['max_offer_quantity'] = $supplier_product['pivot']['max_offer_quantity'];
                    $bill['products'][$i]['offer_expires_at'] = $supplier_product['pivot']['offer_expires_at'];
                    $quantity = $product['quantity']; // requested quantity

                    if ($quantity > $supplier_product['pivot']['max_selling_quantity']) {
                        throw new IncorrectBillException('.' . 'لقد تخطيت العدد الأقصى للطلب : ' . $supplier_product['pivot']['max_selling_quantity'] . ' لدى ' . $supplier->store_name);
                    }

                    if ($supplier_product['pivot']['has_offer']) {
                        // calculate the total price of products taken in the offer
                        $total_price += min(
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
                $not_existing_product = Product::findOrFail($product['id']);
                throw new ProductNotExistForSupplierException($not_existing_product, $supplier);
            }
            $i++;
        }
        return $total_price;

    }

    public function calculatePriceSupplier($bill, $supplier,$billId): float
{
    $total_price = 0.0;

    foreach ($bill['products'] as $product) {
        $billProduct = BillProduct::where('product_id', $product['id'])->where('bill_id',$billId)->first();

        /*if (!$billProduct) {
            $not_existing_product = Product::findOrFail($product['id']);
            throw new ProductNotExistForSupplierException($not_existing_product, $supplier);
        }*/

        $price = $billProduct->buying_price;
        $quantity = $product['quantity'];


        if ($billProduct->has_offer) {

            $offer_quantity = min($billProduct->max_offer_quantity, $quantity);
            $total_price += $offer_quantity * $billProduct->offer_buying_price;
            $quantity -= $offer_quantity;
        }


        if ($quantity > 0) {
            $total_price += $quantity * $price;
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


    public function marketDiscount($market, $total_price)
    {
        $supplier = Auth::user();
        if ($supplier->goals()->count() > 0) {
            $goals = $supplier->goals()->get();
            foreach ($goals as $goal) {
                if ($total_price >= $goal->min_bill_price) {
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





    public function checkProductAvailability($updated_bill, $supplier, $bill)
    {
        $unavailableProducts = [];

        foreach ($updated_bill['products'] as $item) {
            $product = Product::find($item['id']);
            $availableQuantity = $product->suppliers()->wherePivot('supplier_id', $supplier->id)->first()->pivot->quantity ?? 0;
            if ($availableQuantity < $item['quantity']) {
                $unavailableProducts[] = $product->name;
            }
        }

        if (count($unavailableProducts) > 0) {
            $errorProducts = implode(', ', $unavailableProducts);
            return 'الكمية المتاحة لديك من المنتجات ' . $errorProducts . ' غير كافية';
        }

        foreach ($updated_bill['products'] as $item) {
            $product = Product::find($item['id']);
            $pivot = $product->suppliers()->wherePivot('supplier_id', $supplier->id)->first()->pivot;
            $availableQuantity = $pivot->quantity ?? 0;
            if ($availableQuantity >= $item['quantity']) {
                $pivot->quantity = $availableQuantity - $item['quantity'];
                if ($pivot->quantity == 0) {
                    $pivot->is_available = 0;
                }

                $product->suppliers()->updateExistingPivot($supplier->id, $pivot->toArray());

            }
        }
        return null;
    }

    public function removeProducts($product, $bill)
    {
        $products = [];

        foreach ($product['products'] as $product) {
            $quantity = $product['quantity'];
            if ($quantity > 0) {
                $products[] = $product;
            }
            if ($product['quantity'] == 0) {
                $bill->products()->detach($product['id']);
            }
        }
        return [
            'products' => $products
        ];
    }

    public function checkCouponDiscount($bill, $coupon_code): void
    {
        $codes = Coupon::active()->pluck('code')->all();

        if (in_array($coupon_code, $codes)) {
            // the coupon code sent in the bill is correct

            $coupon = Coupon::where('code', $coupon_code)->first();

            $coupon_bil = CouponBill::where('coupon_id', $coupon->id)
                ->where('market_id', $bill->market_id)
                ->first();

            // check to ensure that every coupon_code is used just one time.
            if ($coupon && ! $coupon_bil  && ($bill->total_price > $coupon->min_bill_limit)) {
                $couponBill = new CouponBill();
                $couponBill->coupon_id = $coupon->id;
                $couponBill->bill_id = $bill->id;
                $couponBill->market_id = $bill->market_id;
                $couponBill->save();
            }
        }
    }

}

