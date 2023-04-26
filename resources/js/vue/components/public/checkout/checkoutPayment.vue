<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Methods
			<p></p>
		</h3>

		<form @submit.prevent="this.addPaymentMethod()" id="payment-container" class="checkout-container">
			<input type="hidden" name="_token" :value="csrf">

			<label for="card-holder-name">Card Holder Name</label>
			<input id="card-holder-name" name="card-holder-name" type="text">

			<!-- Stripe Elements Placeholder -->
			<div id="card-element" class="stripe-input"></div>

			<!-- <button id="card-button" data-secret="{{ $intent->client_secret }}"> -->
			<button id="card-button" class="submit" type="submit">
				Add Payment Method
			</button>
		</form>
	</div>

	<div class="checkout-button-container">
		<button @click="pay" id="continue" class="page-button padding">
			Pay Now
			<i class="fa-solid fa-angles-right"></i>
		</button>
	</div>
</template>

<script>
export default {
	props: [
		'stripekey',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	},

	mounted() {
		this.stripe = Stripe(this.stripekey);

		this.elements = this.stripe.elements();
		this.cardElement = this.elements.create('card');

		this.cardElement.mount('#card-element');

		this.cardHolderName = document.getElementById('card-holder-name');
		this.cardButton = document.getElementById('card-button');
	},

	methods: {
		async addPaymentMethod() {
			try {
				this.paymentMethod = await this.stripe.createPaymentMethod(
					'card', this.cardElement, {
					billing_details: { name: this.cardHolderName.value }
				}
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				console.log('----SUCCESS----');
				console.log(this.paymentMethod);
			}
		}
	}
}
</script>