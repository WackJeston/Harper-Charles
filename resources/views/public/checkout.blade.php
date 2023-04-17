@extends('layout')

@section('title', 'Checkout')

@section('content')
  <main class="auth" id="checkout-page">

    <h1 class="title dk" id="deliveryMarker">Checkout</h1>

    @if ($errors->any())
      <div id="publicerror" class="lt">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('message'))
      <div id="publicmessage" class="lt">
        <publicmessage successmessage="{{ session()->get('message') }}" />
      </div>
    @endif

		@switch($action)
				@case('addresses')
					<div id="checkout-timeline">
						<i class="fa-solid fa-circle"></i>
						<div></div>
						<i class="fa-regular fa-circle"></i>
						<div></div>
						<i class="fa-regular fa-circle"></i>
					</div>

					<div id="checkoutaddresses" class="dk checkout-section">
						<checkoutaddresses 
						:deliveryaddressespre="{{ json_encode($deliveryAddresses) }}" 
						:defaultdelivery="{{ $defaultDelivery }}" 
						:billingaddressespre="{{ json_encode($billingAddresses) }}" 
						:defaultbilling="{{ $defaultBilling }}" 
						/>
					</div>

					@break
				@case('payment')
					<div id="checkout-timeline">
						<i class="fa-solid fa-circle-check"></i>
						<div></div>
						<i class="fa-solid fa-circle"></i>
						<div></div>
						<i class="fa-regular fa-circle"></i>
					</div>

					<div id="checkoutpayment" class="dk checkout-section">
						<checkoutpayment 
						:intent="{{ json_encode($intent) }}"
						/>
					</div>

					@break
				@default
						
		@endswitch

  </main>
@endsection