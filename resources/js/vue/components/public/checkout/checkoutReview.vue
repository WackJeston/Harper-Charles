<template>
	<div class="web-box section-width">
		<h3 id="record-header">
			Please check your details before continuing to payment
			<p></p>
		</h3>

		<div id="review-container" class="checkout-container">
			<div class="saved-records-container">

				<!-- Checkout -->
				<div class="saved-record columns cols-2">
					<div class="saved-record-column">
						<ul>
							<li>{{ this.checkout.billingAddress.firstName }} {{ this.checkout.billingAddress.lastName }}</li>
							<li>{{ this.checkout.billingAddress.company }}</li>
							<li>{{ this.checkout.billingAddress.line1 }}</li>
							<li>{{ this.checkout.billingAddress.city }}, {{ this.checkout.billingAddress.region }}</li>
							<li>{{ this.checkout.billingAddress.country }}</li>
							<li>{{ this.checkout.billingAddress.postCode }}</li>
							<li>{{ this.checkout.billingAddress.phone }}</li>
							<li>{{ this.checkout.billingAddress.email }}</li>
						</ul>

						<div class="record-buttons">
							<div class="record-button-container">
							</div>

							<div class="record-button-container">
								<a href="/checkout/addresses" class="record-button">Edit <i class="fa-solid fa-pen-to-square"></i></a>
							</div>
						</div>
					</div>

					<div class="saved-record-column">
						<ul>
							<li>{{ this.checkout.deliveryAddress.firstName }} {{ this.checkout.deliveryAddress.lastName }}</li>
							<li>{{ this.checkout.deliveryAddress.company }}</li>
							<li>{{ this.checkout.deliveryAddress.line1 }}</li>
							<li>{{ this.checkout.deliveryAddress.city }}, {{ this.checkout.deliveryAddress.region }}</li>
							<li>{{ this.checkout.deliveryAddress.country }}</li>
							<li>{{ this.checkout.deliveryAddress.postCode }}</li>
							<li>{{ this.checkout.deliveryAddress.phone }}</li>
							<li>{{ this.checkout.deliveryAddress.email }}</li>
						</ul>

						<div class="record-buttons">
							<div class="record-button-container">
							</div>

							<div class="record-button-container">
								<a href="/checkout/addresses" class="record-button">Edit <i class="fa-solid fa-pen-to-square"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- <div class="web-box section-width">
		<h3 class="checkout-header">
			<i class="fa-solid fa-cube"></i>
			Items
			<p>{{ checkout.items }} Items | Total: £{{ checkout.total }}</p>
		</h3>

		<div id="products-container" class="checkout-container">
			<div class="saved-records-container">

				<ul v-for="(product, i) in this.checkout.lines" class="saved-record selected-record">
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
				</ul>
			</div>
		</div>
	</div> -->

	<div class="checkout-button-container section-width">
		<span>{{ checkout.count }} Items | Total: £{{ checkout.total }}</span>
		<a :href="'/checkoutContinueReview/' + this.checkout.userId" id="continue" class="page-button padding">
			Pay Now
			<i class="fa-solid fa-angles-right"></i>
		</a>
	</div>
</template>

<script>
export default {
	props: [
		'checkout',
	],
}
</script>