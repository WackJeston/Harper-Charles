@extends('pdf')

@section('title', 'Invoice')

@section('content')
	<style>
		ul {
			padding: none;
			list-style: none;
			font-size: 0.9rem;
		}

		div.addresses {
			float: left;
		}

		div.addresses ul {
			background-color: #cecfd0;
			width: 250px;
			border-radius: 5px;
			padding: 10px 15px;
		}

		div.invoice-details {
			float: right;
		}

		div.invoice-details ul.comp-address {
			text-align: right;
		}

		div.invoice-details #order-info {
			background-color: #cecfd0;
			border-radius: 5px;
			padding: 10px 15px;
		}

		div.invoice-details #order-info p {
			margin: 0;
		}
	</style>
  <main class="dk order-successful" id="checkout-page">

    <div class="invoice-top">
			<div class="addresses">

				<ul id="billingAddress">
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

				<ul id="deliveryAddress">
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
			</div>

			<div class="invoice-details">
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

				<div id="order-info">
					<p>Total: £{{ $order->total }}</p>
				</div>
			</div>
			
		</div>

  </main>
@endsection
