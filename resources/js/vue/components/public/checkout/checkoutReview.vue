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
						<td class="quantity">Qty: {{ line.quantity }}</td>
						<td class="price">
							<span>£{{ line.price }}</span>
							<small class="hide-desktop">£{{ line.total }}</small>
						</td>
						<td class="align-right hide-mobile">£{{ line.total }}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td class="align-right total">Total:	£{{ checkout.total }}</td>
					</tr>
				</tfoot>
			</table>

			<div class="saved-records-columns cols-2">
				<div class="saved-records-container">
					<div class="saved-record">
						<ul>
							<li class="billing-address">Billing Address</li>
							<li class="first">{{ this.checkout.billingFirstName }} {{ this.checkout.billingLastName }}</li>
							<li>{{ this.checkout.billingCompany }}</li>
							<li>{{ this.checkout.billingLine1 }}</li>
							<li>{{ this.checkout.billingCity }}, {{ this.checkout.billingRegion }}</li>
							<li>{{ this.checkout.billingCountry }}</li>
							<li>{{ this.checkout.billingPostCode }}</li>
							<li>{{ this.checkout.billingPhone }}</li>
							<li>{{ this.checkout.billingEmail }}</li>
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
							<li class="first">{{ this.checkout.deliveryFirstName }} {{ this.checkout.deliveryLastName }}</li>
							<li>{{ this.checkout.deliveryCompany }}</li>
							<li>{{ this.checkout.deliveryLine1 }}</li>
							<li>{{ this.checkout.deliveryCity }}, {{ this.checkout.deliveryRegion }}</li>
							<li>{{ this.checkout.deliveryCountry }}</li>
							<li>{{ this.checkout.deliveryPostCode }}</li>
							<li>{{ this.checkout.deliveryPhone }}</li>
							<li>{{ this.checkout.deliveryEmail }}</li>
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