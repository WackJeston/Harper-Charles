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
					<i class="fa-solid fa-circle"></i>
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
						:data="{{ json_encode($paymentElementData) }}"
						:appname="{{ json_encode(ENV('APP_NAME')) }}"
						:klaviyo="{{ $klaviyoResult }}"
					/>
				</div>
				@break
		@endswitch


  </main>
@endsection