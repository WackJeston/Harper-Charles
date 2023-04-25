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
						{{-- <checkoutpayment 
							stripekey="{{ env('STRIPE_KEY') }}"
							intent="{{ $intent }}"
						/> --}}

						<div class="web-box">
							<h3 id="checkout-header">
								<i class="fa-solid fa-wallet"></i>
								Payment Methods
								<p></p>
							</h3>
					
							<div id="payment-container" class="checkout-container">
								<form action="">

									<label for="card-holder-name">Card Holder Name</label>
									<input id="card-holder-name" name="card-holder-name" type="text">

									<!-- Stripe Elements Placeholder -->
									<div id="card-element" class="stripe-input"></div>
									
									{{-- <button id="card-button" data-secret="{{ $intent->client_secret }}"> --}}
									<button id="card-button" class="submit">
										Add Payment Method
									</button>
									
								</form>
							</div>
						</div>
					
						{{-- <button class="page-button padding" id="continue">
							Continue To Payment
							<i class="fa-solid fa-angles-right"></i>
						</button> --}}
					</div>

					<script src="https://js.stripe.com/v3/"></script>
 
					<script>
						// const form = document.querySelector('#payment-container form');

						// form.addEventListener('submit', function(event) {
						// 	event.preventDefault();
						// });

						const stripe = Stripe('pk_test_51MQu7XKpS3Hd40Fv2WokrlsM0f769XlgMIv1r1lIMRQHSp5UCGs7UT86vH5GFX0MpTTtUgeOpGulniVCU1MxVWYx00Eai1ijBR');
				
						const elements = stripe.elements();
						const cardElement = elements.create('card');
				
						cardElement.mount('#card-element');

						const cardHolderName = document.getElementById('card-holder-name');
						const cardButton = document.getElementById('card-button');
						
						cardButton.addEventListener('click', async (e) => {
							try {
								const { paymentMethod, error } = await stripe.createPaymentMethod(
									'card', cardElement, {
										billing_details: { name: cardHolderName.value }
									}
								);
								
							} catch (error) {
								console.log('----ERROR----');
								console.log(error);

							} finally {
								console.log('----SUCCESS----');
								console.log(paymentMethod);
							}

							// const { paymentMethod, error } = await stripe.createPaymentMethod(
							// 	'card', cardElement, {
							// 		billing_details: { name: cardHolderName.value }
							// 	}
							// );
					
							// if (error) {
							// 	console.log('----ERROR----');
							// 	console.log(error);
							// } else {
							// 	console.log('----SUCCESS----');
							// 	console.log(paymentMethod);
							// }
						});
					</script>

					@break
				@default
						
		@endswitch


  </main>
@endsection