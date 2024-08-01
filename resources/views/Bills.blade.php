<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl; /* Right-to-left text direction for Arabic */
            text-align: right;
            margin: 20px;
            background-color: #f9f9f9;
        }
        section {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .first, .totals {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .totals div {
            flex: 1;
            text-align: center;
        }
        .table {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .discount {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @php
    $products = collect($bill->products)->map(function ($product) {
        return [
            'quantity' => $product['pivot']['quantity'],
            'name' => $product['name'],
            'size' => $product['size'],
            'price' => $product['pivot']['buying_price']
        ];
    });

    $billData = [
        'number' => $bill->id,
        'date' => $bill->created_at_formatted,
        'paymentMethod' => $bill->payment_method,
        'storeName' => $bill->supplier->store_name,
        'status' => $bill->status,
        'marketName' => $bill->market->store_name,
        'numberMarket'=>$bill->market->phone_number,
        'items' => $products,
        'total_price' => $bill->total_price,
        'savings' => $bill->waffarnalak,
        'total_price_after_discount'=>$bill->total_price_after_discount,
        'additional_price'=>$bill->additional_price,
        'location_details'=>$bill->market->location_details,
        'city'=>$bill->market->city->name,

    ];
@endphp
    <section>

        <h1>   <span>#{{ $billData['number'] }}</span>  رقم الفاتورة </h1>
        <div class="first">
            <div>
                <p> {{ $billData['storeName'] }} <strong>الشراء من:</strong> </p>
                <p>{{ $billData['date'] }}<strong>تاريخ الطلب:</strong> </p>
                <p>{{ $billData['paymentMethod'] }}<strong>طريقة الدفع:</strong> </p>
                <p>{{ $billData['numberMarket'] }}<strong>رقم موبايل العميل:</strong></p>
                <p>{{ $billData['location_details'] }}<strong>عنوان العميل:</strong></p>
                <p>{{ $billData['city'] }}<strong>منطقة العميل:</strong></

            </div>
            <p> {{ $billData['status'] }}</p>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>السعر</th>
                        <th>اسم المنتج</th>
                        <th>الكمية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($billData['items'] as $item)
                        <tr>
                            <td>{{ $item['price'] }} جنيه</td>
                            <td>{{ $item['name'] }}</td>
                            <td>×{{ $item['quantity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <div>
                <p>     {{ $billData['total_price'] }}    <strong>اجمالي الفاتورة:</strong></p>
                <p>   {{ $billData['total_price_after_discount']}}   <strong>اجمالي الفاتورة بعد الخصم:</strong></p> <!-- Example discount applied -->
            </div>
            <div>
                <p>   5  <strong>قيمة التوفير:</strong> </p>
                <p>  {{ $billData['total_price_after_discount'] +$billData['additional_price'] }} <strong>اجمالي الفاتورة النهائي:</strong> </p>
            </div>
         <div class="discount">
            <p>  {{ ($billData['savings']+$billData['total_price'])*0.015 }}    <strong>الموفراتي وفر لك:</strong></p>
        </div>
    </div>
    </section>
</body>
</html>
