<?php

namespace App\Http\Controllers\Api\V1\Markets;

use App\Http\Controllers\Controller;
use App\Models\{
    Offer,
    SupplierCategory
};
use Illuminate\Http\Request;

class StartingPageController extends Controller
{
    /**
     * get starting-page related data.
     */
    public function __invoke(Request $request)
    {
        $offers = Offer::latest()->get();
        $categories = SupplierCategory::get(['id', 'type']);
        return response()->json([
            'offers' => $offers ,
            'supplier_categories' => $categories
        ]);
    }
}
