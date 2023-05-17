<template>
	<div class="web-box" id="paymentMarker">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Method
			<p></p>
		</h3>

		<div id="payment-container" class="checkout-container">
			<div class="saved-records-container">
				<ul v-for="(method, i) in this.paymentMethods" class="saved-record" :id="'method-' + method.id"
					:class="[method.defaultShipping == 1 ? 'selected-record' : '']"
					@click.stop="this.selectPaymentMethod($event, method.id)">
					<li>
						{{ method.brand }} 
						<i v-if="method.brand == 'Amex'" class="fa-brands fa-cc-amex"></i>
						<i v-if="method.brand == 'Diners'" class="fa-brands fa-cc-diners-club"></i>
						<i v-if="method.brand == 'Discover'" class="fa-brands fa-cc-discover"></i>
						<i v-if="method.brand == 'Jcb'" class="fa-brands fa-cc-jcb"></i>
						<i v-if="method.brand == 'Mastercard'" class="fa-brands fa-cc-mastercard"></i>
						<i v-if="method.brand == 'Visa'" class="fa-brands fa-cc-visa"></i>
					</li>
					<li>{{ method.last4 }}</li>
					<li>{{ method.exp }}</li>
					<li>{{ method.postcode }}</li>
					<i @click.stop="this.deletePaymentMethod(method.id)" class="fa-solid fa-square-xmark popup-label-button">
						<div class="popup-label-container">
							<span class="popup-label">Delete method</span>
						</div>
					</i>
					<i v-if="method.defaultShipping == 1" class="fa-regular fa-circle-check"></i>
				</ul>
			</div>

			<button v-if="this.paymentMethods.length > 0" class="record-toggle page-button" @click="this.form = !this.form">
				<i v-if="this.form" class="fa-solid fa-angle-up"></i>
				<i v-else class="fa-solid fa-angle-down"></i>
				Add Payment Method
			</button>

			<!-- <form @submit.prevent="addPaymentMethod()" enctype="multipart/form-data"
			:style="[(this.form == true || this.paymentMethods.length == 0) ? { maxHeight: '300px' } : { maxHeight: '0px' }]">
				<input type="hidden" name="_token" :value="csrf"> -->

				<!-- Stripe Elements Placeholder -->
				<!-- <div id="card-element" class="stripe-input"
				:style="[this.paymentMethods.length > 0 ? { marginTop: '20px' } : { marginTop: '0px' }]"></div>

				<button id="card-button" class="submit" type="submit">
					Add Payment Method
				</button>
			</form> -->
			
			<!-- STRIPE PAYMENT ELEMENT (Needs Domain Confirmation) -->
			<form @submit.prevent="addPaymentMethod()" enctype="multipart/form-data" id="payment-form"
			:style="[(this.form == true || this.paymentMethods.length == 0) ? { maxHeight: '800px' } : { maxHeight: '0px' }]">
				<input type="hidden" name="_token" :value="csrf">

				<!-- Stripe Elements Placeholder -->
				<div id="payment-element" class="stripe-element"
				:style="[this.paymentMethods.length > 0 ? { marginTop: '20px' } : { marginTop: '0px' }]"></div>

				<button id="payment-button" class="submit" type="submit">
					Add Payment Method
				</button>
			</form>
		</div>
	</div>
		
		

	<div class="checkout-button-container">
		<button @click="this.checkoutContinue()" id="continue" class="page-button padding">
			Review Order
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
		'paymentmethods',
		'clientsecret',
		'total',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			form: this.paymentmethods.length > 0 ? false : true,
			paymentMethods: this.paymentmethods,
			methodSelected: 0,
		}
	},

	mounted() {
		this.stripe = Stripe(this.stripekey);

		this.options = {
			mode: 'payment',
			clientSecret: this.clientsecret,
			currency: 'gbp',
			amount: this.total * 100,
			// Fully customizable with appearance API.
			// appearance: {/*...*/},
		};

		this.elements = this.stripe.elements(this.options);

		this.paymentElement = this.elements.create('payment');
		this.paymentElement.mount('#payment-element');
	},

	methods: {
		checkoutContinue() {
			if (this.methodSelected != 0) {
				window.location.href = '/checkoutContinuePayment/' + this.methodSelected;
			} else {
				this.errorMessage();
			}
		},

		errorMessage(toggle = true) {
			let header = document.querySelector('#checkout-header');
			let errorMessage = document.querySelector('#checkout-header p');

			if (toggle == true) {
				window.location.href = '#paymentMarker';

				header.style.backgroundColor = '#FF6666';
				errorMessage.innerHTML = 'Please select a payment address.';

			} else {
				header.style.backgroundColor = '#5E6264';
				errorMessage.innerHTML = '';
			}
		},

		selectPaymentMethod(submitEvent, id) {
			const buttons = [
				'fa-solid fa-square-xmark popup-label-button',
				'fa-solid fa-square popup-label-button',
			];

			if (submitEvent == null || !buttons.includes(submitEvent.target.className)) {
				let previousTicks = document.querySelectorAll('#payment-container .fa-circle-check');
				previousTicks.forEach(tick => {
					tick.remove();
				});

				let previousMethod = document.querySelector('#payment-container .selected-record');
				if (previousMethod != null) {
					previousMethod.classList.remove('selected-record');
				}

				let targetMethod = document.querySelector('#payment-container #method-' + id);
				targetMethod.classList.add('selected-record');
				let innerMethod = targetMethod.innerHTML + '<i class="fa-regular fa-circle-check"></i>';
				targetMethod.innerHTML = innerMethod;

				this.methodSelected = id;
				this.errorMessage(false);

			} else if (submitEvent.target.className == buttons[0]) {
				this.deletePaymentMethod(id);
			}
		},

		async deletePaymentMethod(id) {
			try {
				this.result = await this.$http.post(
					'/checkoutDeletePaymentMethod/' + id,
					{ name: "delete-method" }
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				this.paymentMethods.forEach(method => {
					if (method.id == id) {
						console.log('----DELETED----');
						let index = this.paymentMethods.indexOf(method);
						this.paymentMethods.splice(index, 1);
					}
				});
			}
		},

		async addPaymentMethod() {
			try {
				this.result = await this.stripe.createPaymentMethod(
					'payment', this.paymentElement, {
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
				console.log('Result: ' + this.result);
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

				this.brand = this.result.data.card.brand;
				this.brandFirstLetter = this.brand.charAt(0).toUpperCase();
				this.brandRest = this.brand.slice(1);
				this.brand = this.brandFirstLetter + this.brandRest;
				this.expYear = this.result.data.card.exp_year.toString().slice(2, 4);

				let newMethod = [];

				newMethod['id'] = this.result.data.id;
				newMethod['brand'] = this.brand;
				newMethod['last4'] = this.result.data.card.last4;
				newMethod['exp'] = this.result.data.card.exp_month + '/' + this.expYear;
				newMethod['postcode'] = this.result.data.billing_details.address.postal_code;

				this.paymentMethods.push(newMethod);
				this.form = false;

				let form = document.querySelector('#payment-container form');
				form.reset();

				setTimeout(() => {
					this.selectPaymentMethod(null, this.result.data.id);
				}, 10);
			}
		},
	}
}
</script>