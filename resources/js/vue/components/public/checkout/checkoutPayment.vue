<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Methods
			<p></p>
		</h3>

		<div id="payment-container" class="checkout-container">
			<stripe-element-payment ref="paymentRef" :pk="pk" :elements-options="elementsOptions"
				:confirm-params="confirmParams" />
			<button @click="pay">Pay Now</button>
		</div>
	</div>

	<!-- <div class="checkout-button-container">
		<button @click="pay" id="continue" class="page-button padding">
			Pay Now
			<i class="fa-solid fa-angles-right"></i>
		</button>
	</div> -->
</template>

<script>
import { StripeElementCard } from '@vue-stripe/vue-stripe';

export default {
	components: {
		StripeElementCard,
	},

	props: [
		'stripekey',
	],

	data() {
		return {
			pk: this.stripekey,
			elementsOptions: {
				appearance: {}, // appearance options
			},
			confirmParams: {
				return_url: '/checkout/success', // success url
			},
		};
	},

	mounted() {
		this.generatePaymentIntent();
	},

	methods: {
		async generatePaymentIntent() {
			try {
				this.paymentIntent = await this.$http.post(
					'/checkoutCreatePaymentIntent',
					{ name: "generate-payment-intent" }
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				console.log('----SUCCESS----');
				console.log(this.paymentIntent);
				this.elementsOptions.clientSecret = paymentIntent.client_secret;
			}
			// const paymentIntent = await apiCallToGeneratePaymentIntent(); // this is just a dummy, create your own API call
		},

		pay() {
			this.$refs.paymentRef.submit();
		},
	}
}
</script>