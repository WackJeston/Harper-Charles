<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-wallet"></i>
			Payment Methods
			<p></p>
		</h3>

		<div id="payment-container" class="checkout-container">
			<div class="saved-records-container">
				<ul v-for="(method, i) in this.paymentMethods" class="saved-record" :id="'method-' + method.id"
					:class="[method.defaultShipping == 1 ? 'selected-record' : '']"
					@click.stop="this.selectPaymentMethod($event, method.id)">
					<li>{{ method.name }}</li>
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
					<li>{{ method.country }}</li>
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

			<form @submit.prevent="addPaymentMethod()" enctype="multipart/form-data"
			:style="[(this.form == true || this.paymentMethods.length == 0) ? { maxHeight: '300px' } : { maxHeight: '0px' }]">
				<input type="hidden" name="_token" :value="csrf">

				<!-- Stripe Elements Placeholder -->
				<div id="card-element" class="stripe-input"
				:style="[this.paymentMethods.length > 0 ? { marginTop: '20px' } : { marginTop: '0px' }]"></div>

				<!-- <button id="card-button" data-secret="{{ $intent->client_secret }}"> -->
				<button id="card-button" class="submit" type="submit">
					Add Payment Method
				</button>
			</form>
		</div>
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
		'paymentmethods',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			form: this.paymentmethods.length > 0 ? false : true,
			paymentMethods: this.paymentmethods,
			methodSelected: null,
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

				let newMethod = document.querySelector('#payment-container #method-' + id);
				newMethod.classList.add('selected-record');
				let innerMethod = newMethod.innerHTML + '<i class="fa-regular fa-circle-check"></i>';
				newMethod.innerHTML = innerMethod;

				this.methodSelected = id;

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

				this.brand = this.result.data.card.brand;
				this.brandFirstLetter = this.brand.charAt(0).toUpperCase();
				this.brandRest = this.brand.slice(1);
				this.brand = this.brandFirstLetter + this.brandRest;
				this.expYear = this.result.data.card.exp_year.toString().slice(2, 4);

				this.newMethod = {};

				this.newMethod['id'] = this.result.data.id,
				this.newMethod['name'] = this.result.data.billing_details.name,
				this.newMethod['brand'] = this.brand,
				this.newMethod['last4'] = this.result.data.card.last4,
				this.newMethod['exp'] = this.result.data.card.exp_month + '/' + this.expYear,

				console.log(this.newMethod);

				this.paymentMethods.push(this.newMethod);
				this.form = false;

				let form = document.querySelector('#payment-container form');
				form.reset();

				// setTimeout(() => {
				// 	this.selectAddress(null, type, this.result.data.id);

				// 	if (this.result.data.defaultShipping == 1 || this.result.data.defaultBilling == 1) {
				// 		this.defaultAddress(type, this.result.data.id);
				// 	}
				// }, 10);
			}
		},
	}
}
</script>