<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Methods
			<p></p>
		</h3>

		<div id="payment-container" class="checkout-container">
			<stripe-checkout ref="paymentRef" :pk="pk" :elements-options="elementsOptions" :confirm-params="confirmParams" />
		</div>
	</div>

	<button @click="pay" id="continue" class="page-button padding">
		Pay Now
		<i class="fa-solid fa-angles-right"></i>
	</button>
</template>

<script>
import { StripeElementPayment } from '@vue-stripe/vue-stripe';

export default {
	components: {
		StripeElementPayment,
	},

	props: [
		'intent',
	],

	data() {
		this.publishableKey = process.env.STRIPE_PUBLISHABLE_KEY;

		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			pk: 'your-publishable-key',
			elementsOptions: {
				appearance: {}, // appearance options
			},
			confirmParams: {
				return_url: 'http://localhost:8080/success', // success url
			},
		}
	},

	mounted() {
		this.elementsOptions.clientSecret = this.intent.client_secret;
	},

	methods: {
		pay() {
			this.$refs.paymentRef.submit();
		},
	},
}
</script>