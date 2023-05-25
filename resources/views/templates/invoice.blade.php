@extends('pdf')

@section('title', 'Document')

@section('content')
	<style>
		#invoice-top {
			width: 100%;
			margin-bottom: 20px;
		}

		#invoice-top ul {
			padding: none;
			list-style: none;
			font-size: 0.9rem;
		}

		#invoice-top ul.address {
			background-color: #cecfd0;
			width: 250px;
			border-radius: 5px;
			padding: 10px 15px;
		}

		#invoice-top ul.comp-address {
			float: right;
			text-align: right;
		}

		#order-info {
			width: 250px;
			background-color: #cecfd0;
			border-radius: 5px;
			padding: 10px 15px;
		}

		#order-info p {
			margin: 0;
		}

		#invoice-products	table {
			width: 100%;
			border-collapse: collapse;
		}

		table th, table td {
			padding: 5px;
		}

		table th {
			border-bottom: 1px solid #5E6264;
		}

		table tbody td {
			border-bottom: 1px solid #cecfd0;
		}

		table img {
			border-radius: 3px;
		}
	</style>
  <main class="dk order-successful" id="checkout-page">

    <section id="invoice-top">
			<ul class="comp-address">
				@foreach ($contact['email'] as $email)
				<li>{{ $email }}</li>
				@endforeach
				@foreach ($contact['phone'] as $phone)
				<li>{{ $phone }}</li>
				@endforeach
					<li>{{ $contact['line1'] }}</li>
				@if ($contact['line2'] != '')
					<li>{{ $contact['line2'] }}</li>
				@endif
				@if ($contact['line3'] != '')
					<li>{{ $contact['line3'] }}</li>
				@endif
				<li>{{ $contact['city'] }}</li>
				<li>{{ $contact['region'] }}</li>
				<li>{{ $contact['country'] }}</li>
				<li>{{ $contact['postcode'] }}</li>
			</ul>			

			<ul class="address">
				<li><strong>Billing Address</strong></li>
				<li>{{ $billingAddress->line1 }}</li>
				@if ($billingAddress->line2 != '')
					<li>{{ $billingAddress->line2 }}</li>
				@endif
				@if ($billingAddress->line3 != '')
					<li>{{ $billingAddress->line3 }}</li>
				@endif
				<li>{{ $billingAddress->city }}</li>
				<li>{{ $billingAddress->region }}</li>
				<li>{{ $billingAddress->country }}</li>
				<li>{{ $billingAddress->postcode }}</li>
			</ul>

			<ul class="address">
				<li><strong>Delivery Address</strong></li>
				<li>{{ $deliveryAddress->line1 }}</li>
				@if ($deliveryAddress->line2 != '')
					<li>{{ $deliveryAddress->line2 }}</li>
				@endif
				@if ($deliveryAddress->line3 != '')
					<li>{{ $deliveryAddress->line3 }}</li>
				@endif
				<li>{{ $deliveryAddress->city }}</li>
				<li>{{ $deliveryAddress->region }}</li>
				<li>{{ $deliveryAddress->country }}</li>
				<li>{{ $deliveryAddress->postcode }}</li>
			</ul>

			<div id="order-info">
				<p>Order #: {{ $order->id }}</p>
				<p>Order Date: {{ $order->date }}</p>
			</div>
		</section>

		<section id="invoice-products">
			<table>
				<thead align="left">
					<tr>
						<th>#</th>
						<th></th>
						<th>Title</th>
						<th>Quantity</th>
						<th>Price</th>
						<th align="right">Subtotal</th>
					</tr>
				</thead>
				<tbody align="left">
					@foreach ($products as $i => $product)
							<tr>
								<td>{{ $product->id }}</td>
								<td><img width="30" src="https://hc-main.s3.eu-west-2.amazonaws.com/assets/{{ $product->fileName }}" alt="{{ $product->title }}"></td>
								<td>{{ $product->title }}</td>
								<td>{{ $product->quantity }}</td>
								<td>{{ $product->price }}</td>
								<td align="right">{{ number_format((float)$product->price * $product->quantity, 2, '.', '') }}</td>
							</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td>Total</td>
						<td>{{ number_format((float)$order->total, 2, '.', '') }}</td>
					</tr>
				</tfoot>
			</table>
		</section>

  </main>
@endsection
