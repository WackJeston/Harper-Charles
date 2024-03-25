<template>
	<header>

		<div class="section-width">
			<div id="checkout-title">
				<h1 v-if="this.action == 'address'">Delivery Address</h1>
				<h1 v-else-if="this.action == 'payment'">Payment Method</h1>
				<h1 v-else-if="this.action == 'summary'">Summary</h1>

				<a href="/basket">Continue Shopping</a>
			</div>

			<a class="logo-link" href="/">
				<img async :src="this.publicasset + 'website-logo.svg'" alt="logo" class="logo">
			</a>
		</div>

	</header>
</template>


<script>
export default {
	props: [
		'publicasset',
		'action',
	],

	mounted() {
		this.setScrollListener();
	},

	methods: {
		setScrollListener() {
			const page = document.querySelector('#page-container');
			const header = document.querySelector('header');

			page.addEventListener('scroll', function() {
				let scrollLimit = window.innerWidth > 640 ? 70 : 55;

				if (page.scrollTop > scrollLimit) {
					header.classList.add('scroll');
				
				} else {
					header.classList.remove('scroll');
				}
			});
		},
	},
};
</script>
