<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Methods
			<p></p>
		</h3>

		<div id="payment-container" class="checkout-container">
			<stripe-element-card ref="elementRef" :pk="stripeKey" @token="tokenCreated" />
			<button @click="submit">Save</button>
		</div>
	</div>

	<!-- <button @click="pay" id="continue" class="page-button padding">
		Pay Now
		<i class="fa-solid fa-angles-right"></i>
	</button> -->
</template>

<script>
import { StripeElementCard } from '@vue-stripe/vue-stripe';

export default {
	components: {
		StripeElementCard,
	},

	data() {
		this.stripeKey = process.env.STRIPE_KEY;
		return {
			token: null,
		};
	},

	methods: {
		submit() {
			// this will trigger the process
			this.$refs.elementRef.submit();
		},
		tokenCreated(token) {
			console.log(token);
			// handle the token
			// send it to your server
		},
	}
}
</script>