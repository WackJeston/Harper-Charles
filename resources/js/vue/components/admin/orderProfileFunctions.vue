<template>
	<!-- Buttons -->
	<div class="vue-button-row">
		<div>
			<button class="page-button" type="button" onclick="setShowMarker('notes')" :class="{ 'button-active': this.show == 'notes' }" @click="this.show == 'notes' ? this.show = false : this.show = 'notes'">Notes<span v-show="this.notestable.count > 0"> ({{this.notestable.count }})</span></button>
		</div>
		<div>
			<a v-if="this.order.stripeReceipt != null" :href="this.order.stripeReceipt" target="_blank" class="page-button padding"><i class="fa-solid fa-receipt"></i>Stripe Receipt</a>
			<a :href="this.order.invoice" target="_blank" class="page-button padding"><i class="fa-solid fa-file-invoice"></i> Invoice</a>
			<button class="page-button" type="button" onclick="setShowMarker('delivery')" :class="{ 'button-active': this.show == 'delivery' }" @click="this.show == 'delivery' ? this.show = false : this.show = 'delivery'"><i class="fa-solid fa-pen-to-square"></i>Delivery</button>
			<a v-if="this.order.status != 'Complete'" :href="'/order-profileProceed/' + this.order.id" class="page-button pb-info padding">Proceed<i class="fa-solid fa-angle-right button-end-icon"></i></a>
		</div>
	</div>

	<!-- Delivery -->
	<div v-html="this.deliveryform.html" v-show="this.show == 'delivery'" :class="{ 'functions-padding' : this.show == 'delivery' }"></div>

	<!-- Notes -->
	<div v-html="this.notesform.html" v-show="this.show == 'notes'"></div>
	<div v-html="this.notestable.html" v-show="this.show == 'notes'" :class="{ 'functions-padding' : this.show == 'notes' }"></div>
</template>


<script>
	export default {
		props: [
			'pageshowmarker',
			'order',
			'deliveryform',
			'notesform',
			'notestable',
		],

		data() {
			return {
				show: this.pageshowmarker,
			};
		},
	};
</script>
