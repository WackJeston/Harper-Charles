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

				<div class="web-box section-width">
					<h3 id="record-header">
						Please select your preferred payment method.
						<p></p>
					</h3>
			
					<div id="payment-container" class="checkout-container">
						<div id="payment-element">
							<i class="fas fa-spinner fa-spin"></i>
							<!--Stripe.js injects the Payment Element-->
						</div>
				
						<button id="submit" type="submit" name="submit" class="page-button">
							<span id="button-text">Confirm Payment</span>
							{{-- <i class="fas fa-cog fa-spin"></i> --}}
						</button>
				
						<div id="payment-message" class="hidden"></div>
						<div id="error-message">
							<!-- Display error message to your customers here -->
						</div>
					</div>
				</div>

				{{-- <div id="checkoutpayment" class="dk checkout-section">
					<checkoutpayment 
						stripekey="{{ env('STRIPE_KEY') }}"
						:stripeid="{{ auth()->user()->stripe_id }}"
					/>
				</div> --}}

				{{-- <script src="https://js.stripe.com/v3/"></script> --}}
				@break
		@endswitch


  </main>
@endsection