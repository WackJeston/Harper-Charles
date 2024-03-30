<template>
  <!-- Buttons -->
	<div class="page-button-row">
		<button class="page-button" type="button" :class="{ 'button-active' : show == 'notes' }"
  	@click="show == 'notes' ? show = false : show = 'notes'">Order Notes</button>

		<a :href="this.invoice" target="_blank" class="page-button padding">
			Invoice
			<i class="fa-solid fa-file-invoice"></i>
		</a>
	</div>

	<!-- Notes Form -->
	<form class="web-box dk" v-show="show == 'notes'" :action="'/account/orderAddNote/' + this.order.id" method="POST" enctype="multipart/form-data">
		<i class="fa-solid fa-xmark" @click="show = false"></i>
		<input type="hidden" name="_token" :value="csrf">

		<label for="firstname">Note<span class="red"> *</span></label>
		<textarea type="text" name="note" maxlength="4000"></textarea>

		<button class="submi page-button padding" type="submit">Add Note</button>
	</form>

	<!-- Notes Table -->
	<div v-html="this.notestable.html" v-show="show == 'notes'"></div>

	<div class="wb-container-row">
		<!-- Order INFO -->
		<ul class="web-box dk section-width">
			<li><strong>Order:</strong> #{{ this.order.id }}</li>
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