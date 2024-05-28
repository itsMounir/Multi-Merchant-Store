<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>فاتورة</title>
    <style>
        body {
            direction: rtl;
            font-family: 'DejaVu Sans', sans-serif;
        }
        .invoice-container {
            width: 80%;
            margin: auto;
        }
        .header {
            text-align: center;

        }
        .details, .footer {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #f80101;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f40f0f;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>الموفراتي</h1>
            <p>رقم الفاتورة: {{ $bill->id }}</p>
            <p>تاريخ الفاتورة: {{ Carbon\Carbon::parse($bill->created_at)->format('d/m/Y') }}</p>
        </div>

        <div class="details">
            <h2>تفاصيل الماركت</h2>
            <p>اسم الماركت: {{ $bill->market->store_name }}</p>
            <p>الهاتف: {{ $bill->market->phone_number }}</p>
            <p>المدينة: {{ $bill->market->city_name }}</p>
            <h2>تفاصيل المورّد</h2>
            <p>اسم المورّد: {{ $bill->supplier->store_name }}</p>
            <p>الهاتف: {{ $bill->supplier->phone_number }}</p>

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

        <div class="footer">
            <h2>ملخص الفاتورة</h2>
            <p>السعر: {{ $bill->total_price }} ج.م</p>
            <p>السعر المستحق: {{ $bill->additional_price }} ج.م</p>
            <p>وفرنالك: {{$bill->waffarnalak}} ج.م</p>
            <p>طريقة الدفع: {{ $bill->payment_method }}</p>
            <p>الحالة: {{ $bill->status }}</p>
        </div>
    </div>
</body>
</html>
