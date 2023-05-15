@extends('layout')

@section('title', 'Checkout')

@section('content')
  <main class="auth" id="checkout-page">

		<h1 id="deliveryMarker">Checkout</h1>

    @if ($errors->any())
      <div id="publicerror" class="lt">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('success') || session()->has('info'))
      <div id="publicalert" class="lt">
        <publicalert 
				message="{{ session()->has('success') ? session()->get('success') : session()->get('info') }}"
				type="{{ session()->has('success') ? 'success' : 'info' }}"
				/>
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
					:countries="{{ json_encode($countries) }}"
					/>
				</div>

				@break
			@case('payment')
				<div id="checkout-timeline">
					<a href="/checkout/addresses">
						<i class="fa-solid fa-circle-check"></i>
					</a>
					<div></div>
					<i class="fa-solid fa-circle"></i>
					<div></div>
					<i class="fa-regular fa-circle"></i>
				</div>

				<div id="checkoutpayment" class="dk checkout-section">
					<checkoutpayment 
						stripekey="{{ env('STRIPE_KEY') }}"
						:stripeid="{{ $sessionUser->stripe_id }}"
						:billingaddress="{{ json_encode($billingAddress) }}"
						:paymentmethods="{{ json_encode($paymentMethods) }}"
						{{-- STRIPE PAYMENT ELEMENT (Needs Domain Confirmation) --}}
						:clientsecret="{{ $clientSecret }}"
						:total="{{ $total }}"
					/>
				</div>

				<script src="https://js.stripe.com/v3/"></script>
				@break

			@case('review')
				<div id="checkout-timeline">
					<a href="/checkout/addresses">
						<i class="fa-solid fa-circle-check"></i>
					</a>
					<div></div>
					<a href="/checkout/payment">
						<i class="fa-solid fa-circle-check"></i>
					</a>
					<div></div>
					<i class="fa-solid fa-circle"></i>
				</div>

				<div id="checkoutreview" class="dk checkout-section">
					<checkoutreview 
						:checkout="{{ json_encode($checkout) }}"
						:products="{{ json_encode($products) }}"
						:addresses="{{ json_encode($addresses) }}"
						:paymentmethod="{{ json_encode($paymentMethod) }}"
					/>
				</div>
				
				@break
		@endswitch


  </main>
@endsection