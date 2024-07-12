<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Traits\FirebaseNotification;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use FirebaseNotification;


    public function change(Request $request)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'price' => 'required'
        ]);
        $supplier_id = $request->supplier_id;
        $price = $request->price;
        $supplier = Supplier::find($supplier_id);
        $dis = $supplier->distributionLocations;
        foreach ($dis as $d) {
            $d->min_bill_price = $price;
            $d->save();
        }
    }
}
