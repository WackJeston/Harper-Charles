@extends('pdf')

@section('title', 'Invoice')

@section('content')
	<style>
		#invoice-top {
			width: 100%;
			margin-bottom: 20px;
		}

		#invoice-top ul.comp-address,
		#invoice-top ul.order-details {
			float: right;
			text-align: right;
			padding: none;
		}

		#invoice-top span.stars {
			position: relative;
			top: 5px;
			font-size: 1.2rem;
			letter-spacing: 2px;
		}
	</style>

  <main>
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

			<ul class="web-box">
				<li><strong>Billing Address</strong></li>
				<li>{{ $order->billingFirstName }} {{ $order->billingLastName }}</li>
				<li>{{ $order->billingLine1 }}</li>
				@if ($order->billingLine2 != '')
					<li>{{ $order->billingLine2 }}</li>
				@endif
				@if ($order->billingLine3 != '')
					<li>{{ $order->billingLine3 }}</li>
				@endif
				<li>{{ $order->billingCity }}</li>
				<li>{{ $order->billingRegion }}</li>
				<li>{{ $order->billingCountry }}</li>
				<li>{{ $order->billingPostCode }}</li>
			</ul>

			<ul class="web-box">
				<li><strong>Delivery Address</strong></li>
				<li>{{ $order->deliveryFirstName }} {{ $order->deliveryLastName }}</li>
				<li>{{ $order->deliveryLine1 }}</li>
				@if ($order->deliveryLine2 != '')
					<li>{{ $order->deliveryLine2 }}</li>
				@endif
				@if ($order->deliveryLine3 != '')
					<li>{{ $order->deliveryLine3 }}</li>
				@endif
				<li>{{ $order->deliveryCity }}</li>
				<li>{{ $order->deliveryRegion }}</li>
				<li>{{ $order->deliveryCountry }}</li>
				<li>{{ $order->deliveryPostCode }}</li>
			</ul>

			@switch($paymentMethod['type'])
				@case('card')
					<ul class="web-box">
						<li>{{ $paymentMethod['brand'] }}</li>
						<li><span class="stars">****</span> {{ $paymentMethod['last4'] }}</li>
						<li>{{ $paymentMethod['exp'] }}</li>
						<li>{{ $paymentMethod['postcode'] }}</li>
					</ul>
					@break
						
				@case('paypal')
					<ul class="web-box">
						<li><strong>PayPal</strong></li>
						<li>{{ $paymentMethod['email'] }}</li>
					</ul>
					@break
			@endswitch

			<p>Order Number: #{{ $order->id }}</p>
			<p>Date: {{ $order->date }}</p>
		</section>

		<section id="invoice-products">
			<table>
				<thead align="left">
					<tr>
						<th width="1">#</th>
						<th></th>
						<th>Title</th>
						<th>Quantity</th>
						<th>Price</th>
						<th align="right">Subtotal</th>
					</tr>
				</thead>
				<tbody align="left">
					@foreach ($order->lines as $i => $line)
							<tr>
								<td width="1">{{ $line->productId }}</td>
								<td><img width="30" src="{{ $line->fileName }}" alt="{{ $line->title }}"></td>
								<td>{{ $line->title }}</td>
								<td>{{ $line->quantity }}</td>
								<td>£{{ $line->price }}</td>
								<td align="right">£{{ $line->total }}</td>
							</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="summary">Total</td>
						<td align="right" class="summary">£{{ $order->total }}</td>
					</tr>
				</tfoot>
			</table>
		</section>

  </main>
@endsection
