<template>
	<div class="web-box section-width">
		<h3 id="record-header">
			Please select your preferred payment method.
		</h3>

		<div id="payment-container" class="checkout-container">
			<ul class="alert-box error lt" v-show="this.errorMessage != null">
				<i class="fa-solid fa-xmark" @click="this.errorMessage = null"></i>
				<li>{{ this.errorMessage }}</li>
			</ul>
	
			<div id="payment-message" class="hidden"></div>
			<div id="error-message">
				<!-- Display error message to your customers here -->
			</div>

			<div id="payment-element">
				<!--Stripe.js injects the Payment Element-->
			</div>
	
			<button id="submit" type="submit" name="submit" class="page-button no-margin" @click="this.submitElement()">
				<span id="button-text">Confirm Payment</span>
				<i class="fas fa-cog fa-spin"></i>
			</button>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'data',
	],

	data() {
		return {
			key: this.data.key,
			clientSecret: this.data.clientSecret,
			stripe: null,
			elements: null,
			paymentElement: null,
			primaryColor: '#3b4d57',
			amount: this.data.amount,
			billingDetails: this.data.billingDetails,
			errorMessage: null,
		}
	},

	mounted() {
		this.initialiseStripe();
	},

	methods: {
		initialiseStripe() {
			const options = {
				mode: 'payment',
				captureMethod: 'automatic',
				currency: 'gbp',
				amount: this.amount * 100,
				appearance: {
					theme: 'stripe',
					variables: {
						colorPrimary: this.primaryColour,
						colorDanger: '#dc3545',
						borderRadius: '6px',
					}
				},
			};

			const paymentOptions = {
				layout: {
					type: 'tabs',
				},
				defaultValues: {
					billingDetails: this.billingDetails
				},
				paymentMethodOrder: ['card', 'google_pay', 'apple_pay', 'paypal'],
			};

			this.stripe = window.Stripe(this.key);
			this.elements = this.stripe.elements(options);
			this.paymentElement = this.elements.create('payment', paymentOptions);
			this.paymentElement.mount('#payment-element');
		},

		submitElement() {
			this.errorMessage;

			this.elements.submit().then((result) => {
				if (result.error) {
					console.log(result.error);

				} else {
					let button = document.getElementById('submit');
					button.disabled = true;
					button.querySelector('i').style.display = 'inline-block';

					this.stripe.confirmPayment({
						elements: this.elements,
						clientSecret: this.clientSecret,
						redirect: 'if_required',
						confirmParams: {
							return_url: `https://${window.location.hostname}/checkoutCompleteOrder`
						}
					}).then((result) => {
						if(result.error) {
							console.log('ERROR');
							console.log(result);

							this.errorMessage = result.error.message;

							button.disabled = false;
							button.querySelector('i').style.display = 'none';
							
						} else {
							console.log('SUCCESS');
							console.log(result);

							window.location.href = `https://${window.location.hostname}/checkoutCompleteOrder`;
						}
					});
				}
			});
		}
	},
}
</script>