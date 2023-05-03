<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Methods
			<p></p>
		</h3>

		<form @submit.prevent="addPaymentMethod()" id="payment-container" class="checkout-container">
			<input type="hidden" name="_token" :value="csrf">

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
		'stripeid',
		'billingaddress',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	},

	mounted() {
		console.log(this.billingaddress);

		this.stripe = Stripe(this.stripekey);

		this.elements = this.stripe.elements();
		this.cardElement = this.elements.create('card');

		this.cardElement.mount('#card-element');

		this.cardHolderName = document.getElementById('card-holder-name');
	},

	methods: {
		async addPaymentMethod() {
			try {
				this.result = await this.stripe.createPaymentMethod(
					'card', this.cardElement, {
					billing_details: {
						address: {
							city: this.billingaddress.city,
							country: this.billingaddress.country,
							line1: this.billingaddress.line1,
							line2: this.billingaddress.line2,
							postal_code: this.billingaddress.postCode,
							state: this.billingaddress.region,
						},
						email: this.billingaddress.email,
						name: this.billingaddress.firstName + ' ' + this.billingaddress.lastName,
						phone: this.billingaddress.phone,
					},
					customer: this.stripeid,
				});
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				console.log('----SUCCESS----');
				console.log(this.result.paymentMethod.id);
				this.addPaymentMethod2(this.result.paymentMethod.id);
			}
		},

		async addPaymentMethod2(id) {
			try {
				this.result = await this.$http.post(
					'/checkoutAddPaymentMethod/' + id,
					{ name: "add-payment-method-2" }
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				console.log('----SUCCESS----');
				console.log(this.result);
			}
		},
	}
}
</script>