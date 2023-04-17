<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Methods
			<p></p>
		</h3>

		<div id="payment-container" class="checkout-container">

			<!-- <form id="payment-form" data-secret="{{ this.intent.client_secret }}">
				<input type="hidden" name="_token" :value="csrf">

				<div id="payment-element">
					
				</div>

				<button id="submit">Submit</button>
			</form> -->

			<form id="payment-form">
				<div id="card-element"><!--Stripe.js injects the Card Element--></div>
				<button id="submit">
					<div class="spinner hidden" id="spinner"></div>
					<span id="button-text">Pay now</span>
				</button>
				<p id="card-error" role="alert"></p>
				<p class="result-message hidden">
					Payment succeeded, see the result in your
					<a href="" target="_blank">Stripe dashboard.</a> Refresh the page to pay again.
				</p>
			</form>
		</div>
	</div>

	<!-- <button @click="this.checkoutContinue()" id="continue" class="page-button padding">
		Continue to payment
		<i class="fa-solid fa-angles-right"></i>
	</button> -->
</template>

<script>
// import stripe from 'https://js.stripe.com/v3/';

export default {
	props: [
		'intent',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	},

	mounted() {

	},

	methods: {

	},
}
</script>