<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Bill,
    Supplier,
    Market,
    Notification
};


use Illuminate\Support\Facades\{
    Auth,
    DB
};
use PDF;
use Dompdf\Dompdf;

use Dompdf\Options;
class PdfController extends Controller
{

    public function generate_pdf($billId){

        $supplier = Auth::user();

        $bill = $supplier->bills()->with(['market.city', 'supplier', 'products.category'])->find($billId);

        if (!$bill) {
            return $this->indexOrShowResponse('Not found', 404);
        }

        $productIds = $bill->products->pluck('id');
        $bill->load([
            'products' => function ($query) use ($productIds, $supplier) {
                $query->whereIn('products.id', $productIds)
                      ->join('product_supplier', 'products.id', '=', 'product_supplier.product_id')
                      ->where('product_supplier.supplier_id', $supplier->id)
                      ->select('products.*', 'product_supplier.price as price');
            }
        ]);



        $html = view('Bills',compact('bill'))->toArabicHTML();
        $pdf=new PDF();

        $pdf->loadHtml($html);
        $pdf->render();
        $output = $pdf->output();
        $headers = array(
            "Content-type" => "application/pdf",
        );

        return response()->streamDownload(
            fn () => print($output),
            "bill.pdf",
            $headers
        );

    }



}
