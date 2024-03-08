@extends('body-public-checkout')

@section('title', 'Checkout')

@section('content')
  <main id="checkout-page">

    @if ($errors->any())
      <div id="publicerror" class="lt section-width">
        <publicerror :errormessages="{{ str_replace(array('[', ']'), '', $errors) }}" errorcount="{{ count($errors) }}" />
      </div>
    @endif

    @if (session()->has('success') || session()->has('info'))
      <div id="publicalert" class="lt section-width">
        <publicalert 
				message="{{ session()->has('success') ? session()->get('success') : session()->get('info') }}"
				type="{{ session()->has('success') ? 'success' : 'info' }}"
				/>
      </div>
    @endif



		@switch($action)
			@case('address')
				<div id="checkout-timeline" class="section-width">
					<i class="fa-solid fa-circle"></i>
					<div></div>
					<i class="fa-regular fa-circle"></i>
					<div></div>
					<i class="fa-regular fa-circle"></i>
				</div>

				<div id="checkoutaddresses" class="dk checkout-section">
					<checkoutaddresses 
					:addressespre="{{ json_encode($addresses) }}"
					:countries="{{ json_encode($countries) }}"
					/>
				</div>

				@break

			@case('summary')
				<div id="checkout-timeline" class="section-width">
					<a href="/checkout/address">
						<i class="fa-solid fa-circle-check"></i>
					</a>
					<div></div>
					<i class="fa-solid fa-circle-check"></i>
					<div></div>
					<i class="fa-regular fa-circle"></i>
				</div>

				<div id="checkoutreview" class="dk checkout-section">
					<checkoutreview 
						:checkout="{{ json_encode($checkout) }}"
					/>
				</div>
				
				@break
				
			@case('payment')
				<div id="checkout-timeline" class="section-width">
					<a href="/checkout/address">
						<i class="fa-solid fa-circle-check"></i>
					</a>
					<div></div>
					<a href="/checkout/summary">
						<i class="fa-solid fa-circle-check"></i>
					</a>
					<div></div>
					<i class="fa-solid fa-circle"></i>
				</div>

				<div id="checkoutpayment" class="dk checkout-section">
					<checkoutpayment 
						stripekey="{{ env('STRIPE_KEY') }}"
						:stripeid="{{ auth()->user()->stripe_id }}"
						:billingaddress="{{ json_encode($billingAddress) }}"
						:paymentmethods="{{ json_encode($paymentMethods) }}"
						:clientsecret="{{ $clientSecret }}"
						:total="{{ $total }}"
					/>
				</div>

				<script src="https://js.stripe.com/v3/"></script>
				@break
		@endswitch


  </main>
@endsection