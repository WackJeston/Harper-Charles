@extends('pdf')

@section('title', 'Invoice')

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
				<title>Products</title>
				<thead>
					<tr>
						<th width="10%">#</th>
						<th width="15%"></th>
						<th width="30%">Title</th>
						<th width="15%">Quantity</th>
						<th width="15%">Price</th>
						<th width="15%">Subtotal</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($products as $i => $product)
							<tr>
								<td width="10%">{{ $product->id }}</td>
								<td width="15%"><img width="20" src="https://hc-main.s3.eu-west-2.amazonaws.com/assets/{{ $product->fileName }}" alt="{{ $product->title }}"></td>
								<td width="30%">{{ $product->title }}</td>
								<td width="15%">{{ $product->quantity }}</td>
								<td width="15%">{{ $product->price }}</td>
								<td width="15%">{{ $product->price * $product->quantity }}</td>
							</tr>
					@endforeach
				</tbody>
			</table>
		</section>

  </main>
@endsection
