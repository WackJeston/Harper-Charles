<template>
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-cube"></i>
			Items
			<p>{{ checkout.count }} Items | Total: £{{ checkout.total }}</p>
		</h3>

		<div id="products-container" class="checkout-container">
			<div class="saved-records-container">


				<!-- Products -->
				<ul v-for="(product, i) in this.products" class="saved-record selected-record">
					<a :href="'/product-page/' + product.id" class="saved-record-label-container">
						<h4 class="saved-record-label">{{ product.title }}</h4>
					</a>
					<li>{{ product.subtitle }}</li>
					<li>Quantity: {{ product.quantity }}</li>
					<li>£{{ product.price }}</li>
					<li class="record-image-container">
						<a :href="'/product-page/' + product.id" class="record-image"
						:style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + product.fileName + ')' }"></a>
					</li>

					<!-- <i @click.stop="this.deletePaymentMethod(method.id)" class="fa-solid fa-square-xmark popup-label-button">
						<div class="popup-label-container">
							<span class="popup-label">Delete method</span>
						</div>
					</i> -->
				</ul>
			</div>
		</div>
	</div>
		
	<div class="web-box">
		<h3 id="checkout-header">
			<i class="fa-solid fa-check-to-slot"></i>
			Details
			<p></p>
		</h3>

		<div id="review-container" class="checkout-container">
			<div class="saved-records-container">

				<!-- Checkout -->
				<ul v-for="(address, i) in this.addresses" class="saved-record selected-record">
					<h4 class="saved-record-label"><i class="fa-solid fa-house-chimney"></i> {{ address.type }} Address</h4>
					<li>{{ address.firstName }} {{ address.lastName }}</li>
					<li>{{ address.company }}</li>
					<li>{{ address.line1 }}</li>
					<li>{{ address.city }}, {{ address.region }}</li>
					<li>{{ address.country }}</li>
					<li>{{ address.postCode }}</li>
					<li>{{ address.phone }}</li>
					<li>{{ address.email }}</li>
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

	<div class="checkout-button-container">
		<span>{{ checkout.count }} Items | Total: £{{ checkout.total }}</span>
		<a href="/checkoutContinueReview" id="continue" class="page-button padding">
			Pay Now
			<i class="fa-solid fa-angles-right"></i>
		</a>
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