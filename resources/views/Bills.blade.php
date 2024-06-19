<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            direction: rtl;
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-top: 20px;
        }
        .name, .item-quantity, .price {
    margin: 0 10px;
    flex: 1;
}
.item-quantity {
    flex: 1;
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 10px;
    text-align: center;
}

.price {
    flex: 1;
}
        .savings-message {
            background-color: rgb(123, 255, 0);
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
            padding: 10px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            margin-top: 20px;
        }
        .invoice-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .flex-between {
            display: flex;
            justify-content: space-between;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .text-black-50 {
            color: rgba(0, 0, 0, 0.5);
        }
        .invoice-summary {
            border-top: 1px solid #dee2e6;
            margin-top: 20px;
            padding-top: 20px;
        }
        .item-row {
    display: flex;
    padding: 10px;
    align-items: center;
    text-align: right;
    justify-content: space-between;
}
        .item-text {
            margin-bottom: 0;
        }
        .total-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin-bottom: 10px;
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
        'date' => $bill->created_at,
        'paymentMethod' => $bill->payment_method,
        'storeName' => $bill->supplier->store_name,
        'status' => $bill->status,
        'marketName' => $bill->market->store_name,
        'items' => $products,
        'total' => $bill->total_price,
        'savings' => $bill->waffarnalak,
    ];
@endphp
<div class="container border p-5 rounded-3 mt-5">
    <div class="invoice-header">
        <div class="d-flex flex-column align-items-start">
            <h3 id="invoice-number">رقم الفاتورة: {{ $billData['number'] }}</h3>
            <h5 id="invoice-date" class="text-black-50">التاريخ : {{ $billData['date'] }}</h5>
        </div>
        <div class="d-flex justify-content-between text-black-50">
            <h5 id="payment-method">طريقة الدفع : {{ $billData['paymentMethod'] }}</h5>
            <h5 id="store-name">الشراء من : {{ $billData['storeName'] }}</h5>
        </div>
    </div>
    <div class="invoice-summary border-top mt-3 pt-3">
        <div class="text-right d-flex justify-content-between align-items-center">
            <h5>ملخص الفاتورة</h5>
            <h5 id="invoice-status" class="text-success">{{ $billData['status'] }}</h5>
        </div>
        <div class="text-right">
            <h6 id="market-name">{{ $billData['marketName'] }}</h6>
        </div>
        <div id="invoice-items">
            @foreach ($billData['items'] as $item)
            <div class="item">
                <span class="item-name">{{ $item['name'] }}</span>
                <span class="item-quantity">x{{ $item['quantity'] }}</span>
                <span class="item-price">{{ $item['price'] }} جـ</span>
            </div>
        @endforeach


        </div>
        <div class="d-flex justify-content-between mt-4 mb-2 total-section">
            <h4>إجمالي الفاتورة</h4>
            <h5 id="total-price">{{ $bill['total_price'] }} جـ</h5>
        </div>
        <div class="text-right border border-2 p-2 rounded-2 savings-message" id="savings-message">
            🥳الموفراتي وفر لك {{ $bill['waffarnalak'] }} جـ
        </div>
    </div>
</div>
</body>
</html>
