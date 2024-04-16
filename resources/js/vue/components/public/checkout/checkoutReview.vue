<template>
	<div class="web-box section-width">
		<h3 id="record-header">
			Please check your details before continuing to payment.
		</h3>

		<div id="review-container" class="checkout-container">
			<table>
				<tbody>
					<tr v-for="(line, i) in this.checkout.lines">
						<td><img async :src="line.fileName" height="60"></td>
						<td class="product-title">
							<span>{{ line.title }}</span>
							<small>#{{ line.id }}</small>
						</td>
						<td>Qty: {{ line.quantity }}</td>
						<td class="align-right">£{{ line.price }}</td>
						<td class="align-right">£{{ line.total }}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td class="product-title"></td>
						<td></td>
						<td class="align-right">Total:</td>
						<td class="align-right">£{{ checkout.total }}</td>
					</tr>
				</tfoot>
			</table>

			<div class="saved-records-columns cols-2">
				<div class="saved-records-container">
					<div class="saved-record">
						<ul>
							<li class="billing-address">Billing Address</li>
							<li class="first">{{ this.checkout.billingAddress.firstName }} {{ this.checkout.billingAddress.lastName }}</li>
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
				</div>

				<div class="saved-records-container">
					<div class="saved-record">
						<ul>
							<li class="billing-address">Delivery Address</li>
							<li class="first">{{ this.checkout.deliveryAddress.firstName }} {{ this.checkout.deliveryAddress.lastName }}</li>
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

			<button class="order-note-button" @click="this.notesContainer = !this.notesContainer">
				Add an order note
				<i v-if="!this.notesContainer" class="fa-solid fa-angle-down"></i>
				<i v-else class="fa-solid fa-angle-up"></i>
			</button>

			<div class="order-note-container" :style="[!this.notesContainer ? { maxHeight: '0px' } : { maxHeight: '410px' }]">
				<textarea id="orderNote" placeholder="Write your order note here..." :value="this.checkout.orderNote" maxlength="4000" @change="this.saveOrderNote"></textarea>
			</div>
		</div>
	</div>

	<div class="checkout-button-container section-width">
		<span>{{ checkout.items }} Items | Total: £{{ checkout.total }}</span>
		<a href="/checkout/payment" id="continue" class="page-button padding">
			Continue To Payment
			<i class="fa-solid fa-angles-right"></i>
		</a>
	</div>
</template>

<script>
export default {
	props: [
		'checkout',
	],

	data() {
		return {
			notesContainer: false,
		}
	},

	methods: {
		async saveOrderNote(event) {
			try {
				this.response = await fetch("/checkoutSaveOrderNote/" + event.target.value);
				this.result = await this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			}
		}
	},
}
</script>