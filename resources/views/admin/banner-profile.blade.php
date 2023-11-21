@extends('layout')

@section('title', 'Order Profile')

@section('content')
  <main class="order-profile">

		<div class="link-trail">
      <i class="fa-solid fa-arrow-left"></i>
      <a href="/admin/orders">Orders</a>
    </div>

    <h1 class="dk">Order Profile (#{{ $order->id }})</h1>

    @if ($errors->any())
      <div id="alerterror" class="lt">
        <alerterror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="alertmessage" class="lt">
        <alertmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		<div class="page-button-row">
			<a href="https://hc-main.s3.eu-west-2.amazonaws.com/assets/{{ $order->invoice }}" target="_blank" class="page-button padding">Invoice</a>
		</div>

    <div class="web-box profile-main">
			<div class="wb-row">
				<ul>
					<li><strong>Status:</strong> <span class="string-container">{{ ucfirst($order->status) }}</span></li>
					<li><strong>Total:</strong> £{{ $order->total }}</li>
					<li><strong>{{ ucfirst($order->type) }}:</strong> <a class="display-link" href="/admin/{{ $order->type }}-profile/{{ $order->userId }}">{{ $order->user }} (#{{ $order->userId }})</a></li>
					<li><strong>Order Date:</strong> {{ $order->created_at }}</li>
					<div class="list-row">
						@foreach ($addresses as $address)
							<li class="text-box">
								<strong>{{ ucfirst($address->type) }} Address:</strong>
								<ul>
									<li>{{ $address->firstName }} {{ $address->lastName }}</li>
									<li>{{ $address->line1 }}</li>
									@if ($address->line2)
										<li>{{ $address->line2 }}</li>
									@endif
									@if ($address->line3)
										<li>{{ $address->line3 }}</li>
									@endif
									<li>{{ $address->city }}</li>
									<li>{{ $address->region }}, {{ $address->country }}</li>
									<li>{{ $address->postCode }}</li>
									
									@if ($address->phone)
										<li>{{ $address->phone }}</li>
									@endif
									@if ($address->email)
										<li>{{ $address->email }}</li>
									@endif
								</ul>
							</li>
						@endforeach
					</div>
				</ul>
			</div>
		</div>

		@php
			echo $itemsTable['html'];
		@endphp

  </main>
@endsection
