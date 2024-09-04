<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{

    Bill,
    User,
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

        $user = Auth::user();
        $bill=Bill::find($billId);

        $bill->with(['market.city', 'supplier', 'products.category']);

        if (!$bill) {
            return $this->indexOrShowResponse('Not found', 404);
        }

        $productIds = $bill->products->pluck('id');
        $bill->load([
            'products'
        ])->append('total_price_after_discount');



        $html = view('Bills',compact('bill'))->toArabicHTML();
        $pdf=new Dompdf();

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
