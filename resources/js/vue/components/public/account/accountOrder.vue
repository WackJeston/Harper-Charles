<template>
  <!-- Buttons -->
	<div class="page-button-row">
		<button class="page-button" type="button" :class="{ 'button-active' : show == 'notes' }" @click="show == 'notes' ? show = false : show = 'notes'">Order Notes<span v-show="this.notestable.count > 0"> ({{this.notestable.count }})</span></button>

		<a :href="this.invoice" target="_blank" class="page-button padding">
			Invoice
			<i class="fa-solid fa-file-invoice"></i>
		</a>
	</div>

	<!-- Notes Form -->
	<div v-html="this.notesform.html" v-show="show == 'notes'"></div>

	<!-- Notes Table -->
	<div v-html="this.notestable.html" v-show="show == 'notes'"></div>

	<div class="wb-container-row">
		<!-- Order INFO -->
		<ul class="web-box dk section-width">
			<li><strong>Order Number:</strong> #{{ this.order.id }}</li>
			<li><strong>Date:</strong> {{ this.order.date }}</li>
			<li><strong>Status:</strong> {{ this.order.status }}</li>
		</ul>

		<!-- Delivery Address -->
		<ul class="web-box dk section-width">
			<li><strong>Delivery Address</strong></li>
			<li>{{ this.order.deliveryAddress.line1 }}</li>
			<li>{{ this.order.deliveryAddress.line2 }}</li>
			<li>{{ this.order.deliveryAddress.line3 }}</li>
			<li>{{ this.order.deliveryAddress.city }}</li>
			<li>{{ this.order.deliveryAddress.region }}</li>
			<li>{{ this.order.deliveryAddress.country }}</li>
			<li>{{ this.order.deliveryAddress.postcode }}</li>
		</ul>
	</div>

	<!-- Order Lines -->
	<div v-html="this.itemstable.html" v-show="this.order.lines.length > 0"></div>
</template>


<script>
  export default {
    props: [
      'user',
			'order',
			'invoice',
			'notesform',
			'notestable',
			'itemstable'
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
      };
    },
  };
</script>