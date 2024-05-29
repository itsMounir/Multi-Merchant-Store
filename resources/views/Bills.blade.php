<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N"
        crossorigin="anonymous" />
    <link
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        rel="stylesheet" />
    <style>
        bill {
            direction: rtl;
font-family: 'DejaVu Sans', sans-serif;
        }
        .item-quantity {
            width: 10%;
        }
        .price {
            width: 20%;
        }
        .name {
            width: 70%;
        }
        .savings-message {
            background-color: rgb(123, 255, 0);
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #fcf6f6;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f4f4;
        }
    </style>
</head>
<bill>
    <div class="container border p-5 rounded-3 mt-5">
        <div class="invoice-header">
            <div class="d-flex flex-column align-items-start">
                <h2 id="invoice-number">رقم الفاتورة: {{ $bill['id'] }}</h2>
                <h5 id="invoice-date" class="text-black-50">التاريخ : {{ Carbon\Carbon::parse($bill['created_at'])->format('d/m/Y') }}</h5>


            </div>
            <div class="d-flex justify-content-between text-black-50">
                <h5 id="payment-method">طريقة الدفع : {{ $bill['payment_method'] }}</h5>
                <h5 id="store-name">الشراء من : {{ $bill['supplier']['store_name'] }}</h5>
            </div>
        </div>
        <div class="invoice-summary border-top mt-3 pt-3">
            <div class="text-right d-flex justify-content-between align-items-center">
                <h5>ملخص الفاتورة</h5>
                <h5 id="invoice-status" class="text-success">حالة الفاتورة:{{ $bill['status'] }}</h5>
            </div>
            <div class="text-right">
                <h5 id="market-name">اسم الماركت:{{ $bill['market']['store_name'] }}</h5>
            </div>
            <div class="products">
                <h2>المنتجات</h2>
                <table>
                    <thead>
                        <tr>
                            <th>اسم المنتج</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bill->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ $product->price }} ج.م</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
            <div class="d-flex justify-content-between mt-4 mb-2">
                <h4>إجمالي الفاتورة</h4>
                <h5 id="total-price">{{ $bill['total_price'] }} جـ</h5>
            </div>
            @if($bill['waffarnalak'] >=0)
                <div class="text-right border border-2 p-2 rounded-2 savings-message" id="savings-message">
                    🥳 الموفراتي وفر لك {{ $bill['waffarnalak'] }} جـ
                </div>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</bill>
</html>
