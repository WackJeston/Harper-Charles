<template>
	<div class="web-box section-width">
		<h3 id="record-header">
			Please select your preferred payment method.
			<p></p>
		</h3>

		<div id="payment-container" class="checkout-container">
			
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'stripekey',
		'stripeid',
	],

	data() {
		return {

		}
	},

	methods: {
		
	},
}
</script>