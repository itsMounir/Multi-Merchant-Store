<!DOCTYPE html>
<html lang="ar">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>فاتورة</title>
		<style>
			body {
				font-family: 'DejaVu Sans', sans-serif;
				direction: rtl;
				/* Right-to-left text direction for Arabic */
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

			.first,
			.totals {
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

			table,
			th,
			td {
				border: 1px solid #ccc;
			}

			th,
			td {
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
			<div style="margin: auto;height: 100px;">
				<svg style="width: 100%;height: 100%;" xmlns="http://www.w3.org/2000/svg" width="24.505" height="32.791" viewBox="0 0 24.505 32.791">
					<g id="Group_4" data-name="Group 4" transform="translate(0 0)">
						<g id="Group_3" data-name="Group 3" transform="translate(0 11.358)">
							<g id="Path_1" data-name="Path 1" transform="translate(0.962 4.518)">
								<path id="Path_1-2" data-name="Path 1-2"
									d="M281.389,360.7a8.459,8.459,0,0,1-13.071,7.133,5.235,5.235,0,0,1-.49-.346l-.146-.115a8.455,8.455,0,1,1,13.707-6.672Z"
									transform="translate(-264.474 -352.285)" fill="rgba(0,0,0,0)" />
								<path id="Path_2" data-name="Path 2"
									d="M272.892,355.746a4.983,4.983,0,0,0-2.954,8.976,3.069,3.069,0,0,0,.264.184,4.983,4.983,0,1,0,2.69-9.16m-.017-3.473a8.455,8.455,0,1,1-4.572,15.546,5.66,5.66,0,0,1-.49-.344l-.147-.115a8.455,8.455,0,0,1,5.208-15.086Z"
									transform="translate(-264.458 -352.273)" fill="#d90617" />
							</g>
							<circle id="Ellipse_1" data-name="Ellipse 1" cx="2.01" cy="2.01" r="2.01"
								transform="translate(0 0)" fill="#d90617" />
						</g>
						<path id="Path_2-2" data-name="Path 2-2"
							d="M313.418,290.866c-.013-1.411-.038-2.776-.043-3.038,0-.4-.011-.8-.028-1.211a9.594,9.594,0,0,0-1.035-4.524,8.383,8.383,0,0,0-7.232-4.174,8.163,8.163,0,0,0-3.128,15.753,12.4,12.4,0,0,0,4.868.626c1.048-.021,2.1-.024,3.143-.021h2.107q.641.032,1.3,0A31.652,31.652,0,0,0,313.418,290.866Zm-6.956,0a5.075,5.075,0,0,1-1.495.226h-.049a4.981,4.981,0,1,1,1.544-.224Z"
							transform="translate(-288.923 -277.918)" fill="#d90617" />
					</g>
				</svg>
			</div>
			<h1> <span>#{{ $billData['number'] }}</span> رقم الفاتورة </h1>
			<div class="first">
				<div style="display: flex; justify-content: space-between; align-items:flex-start">
					<div>
						<p> {{ $billData['storeName'] }} <strong>الشراء من:</strong> </p>
						<p>{{ $billData['date'] }}<strong>تاريخ الطلب:</strong> </p>
						<p>{{ $billData['paymentMethod'] }}<strong>طريقة الدفع:</strong> </p>
						<p>{{ $billData['numberMarket'] }}<strong>رقم موبايل العميل:</strong></p>
					</div>
					<div>
						<p>{{ $billData['location_details'] }}<strong>عنوان العميل:</strong></p>
						<p>{{ $billData['city'] }}<strong>منطقة العميل:</strong></p>
						<p> {{ $billData['status'] }}</p>
					</div>
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

						<p> {{ $billData['total_price_after_discount'] +$billData['additional_price'] }} <strong>اجمالي
								الفاتورة النهائي:</strong> </p>
					</div>
					<div class="discount">
						<p> {{ ($billData['savings']+$billData['total_price'])*0.015 }} <strong>الموفراتي وفر
								لك:</strong></p>
					</div>
				</div>
		</section>
	</body>

</html>
