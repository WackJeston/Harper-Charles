<template>
	<div class="checkout-button-container section-width">
		<a href="/" class="page-button padding">
			<i class="fa-solid fa-angles-left"></i>
			Return To Shop
		</a>
		<button class="page-button padding">
			Download Invoice
			<i class="fa-solid fa-file-invoice"></i>
		</button>
	</div>

	<div class="web-box section-width">
		<h3 class="checkout-header">
			<i class="fa-solid fa-check-to-slot"></i>
			Order Details
			<p>{{ this.order.count }} Items | Total: £{{ this.order.total }}</p>
		</h3>

		<div class="order-details">
			<ul id="delivery-address">
				<li><strong>DELIVERY ADDRESS</strong></li>
				<li>{{ this.address.firstName }} {{ this.address.lastName }}</li>
				<li>{{ this.address.company }}</li>
				<li>{{ this.address.line1 }}</li>
				<li>{{ this.address.city }}, {{ this.address.region }}</li>
				<li>{{ this.address.country }}</li>
				<li>{{ this.address.postCode }}</li>
				<li>{{ this.address.phone }}</li>
				<li>{{ this.address.email }}</li>
			</ul>

			<div id="products">
				<a v-for="(product, i) in this.products" :href="'/product-page/' + product.id"
				:style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + product.fileName + ')' }"></a>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'order',
		'products',
		'address',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	},

	mounted() {
		console.log(this.order);
	},

	methods: {
		
	}
}
</script>