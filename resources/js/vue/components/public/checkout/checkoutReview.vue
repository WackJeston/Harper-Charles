<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-check-to-slot"></i>
			Review Order
			<p></p>
		</h3>

		<div id="review-container" class="checkout-container">
			<div class="saved-records-container">

				<!-- Checkout -->
				<ul class="saved-record selected-record">
					<h4 class="saved-record-label">Summary</h4>
					<li>Reference Number: {{ checkout.id }}</li>
					<li>Items: {{ checkout.count }}</li>
					<li>Total: £{{ checkout.total }}</li>
					<!-- <i @click.stop="this.deletePaymentMethod(method.id)" class="fa-solid fa-square-xmark popup-label-button">
						<div class="popup-label-container">
							<span class="popup-label">Delete method</span>
						</div>
					</i> -->
				</ul>

				<!-- Payment Mathod -->
				<ul class="saved-record selected-record">
					<h4 class="saved-record-label"><i class="fa-solid fa-wallet"></i> Payment Method</h4>
					<li>
						{{ this.paymentmethod.brand }} 
						<i v-if="this.paymentmethod.brand == 'Amex'" class="fa-brands fa-cc-amex"></i>
						<i v-if="this.paymentmethod.brand == 'Diners'" class="fa-brands fa-cc-diners-club"></i>
						<i v-if="this.paymentmethod.brand == 'Discover'" class="fa-brands fa-cc-discover"></i>
						<i v-if="this.paymentmethod.brand == 'Jcb'" class="fa-brands fa-cc-jcb"></i>
						<i v-if="this.paymentmethod.brand == 'Mastercard'" class="fa-brands fa-cc-mastercard"></i>
						<i v-if="this.paymentmethod.brand == 'Visa'" class="fa-brands fa-cc-visa"></i>
					</li>
					<li>{{ this.paymentmethod.last4 }}</li>
					<li>{{ this.paymentmethod.exp }}</li>
					<li>{{ this.paymentmethod.postcode }}</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-cube"></i>
			Products
			<p></p>
		</h3>

		<div id="products-container" class="checkout-container">
			<div class="saved-records-container">


				<!-- Products -->
				<ul v-for="(product, i) in this.products" class="saved-record selected-record">
					<h4 class="saved-record-label">{{ product.title }}</h4>
					<li>{{ product.subtitle }}</li>
					<li>Quantity: {{ product.quantity }}</li>
					<li>£{{ product.price }}</li>
					<!-- <i @click.stop="this.deletePaymentMethod(method.id)" class="fa-solid fa-square-xmark popup-label-button">
						<div class="popup-label-container">
							<span class="popup-label">Delete method</span>
						</div>
					</i> -->

					<a :href="'/product-page/' + product.id" class="wb-image"
					:style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + product.fileName + ')' }"></a>
				</ul>
			</div>
		</div>
	</div>
		
		

	<div class="checkout-button-container">
		<button @click="this.checkoutContinue()" id="continue" class="page-button padding">
			Pay Now
			<i class="fa-solid fa-angles-right"></i>
		</button>
	</div>
</template>

<script>
export default {
	props: [
		'checkout',
		'products',
		'addresses',
		'paymentmethod',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	},

	mounted() {
		
	},

	methods: {
		
	}
}
</script>