<template>
	<div class="web-box section-width">
		<div class="checkout-button-container section-width">
			<a href="/shop" class="page-button padding">
				<i class="fa-solid fa-angles-left"></i>
				Return To Shop
			</a>
			<a :href="this.invoice" target="_blank" class="page-button padding">
				Invoice
				<i class="fa-solid fa-file-invoice"></i>
			</a>
		</div>

		<div id="success-container" class="checkout-container">
			<table>
				<tbody>
					<tr v-for="(line, i) in this.order.lines">
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
						<td class="align-right total">Total:	£{{ this.order.total }}</td>
					</tr>
				</tfoot>
			</table>

			<div class="saved-records-columns cols-2">
				<div class="saved-records-container">
					<div class="saved-record">
						<ul>
							<li class="billing-address">Billing Address</li>
							<li class="first">{{ this.order.billingFirstName }} {{ this.order.billingLastName }}</li>
							<li>{{ this.order.billingCompany }}</li>
							<li>{{ this.order.billingLine1 }}</li>
							<li>{{ this.order.billingCity }}, {{ this.order.billingRegion }}</li>
							<li>{{ this.order.billingCountry }}</li>
							<li>{{ this.order.billingPostCode }}</li>
							<li>{{ this.order.billingPhone }}</li>
							<li>{{ this.order.billingEmail }}</li>
						</ul>
					</div>
				</div>

				<div class="saved-records-container">
					<div class="saved-record">
						<ul>
							<li class="delivery-address">Delivery Address</li>
							<li class="first">{{ this.order.deliveryFirstName }} {{ this.order.deliveryLastName }}</li>
							<li>{{ this.order.deliveryCompany }}</li>
							<li>{{ this.order.deliveryLine1 }}</li>
							<li>{{ this.order.deliveryCity }}, {{ this.order.deliveryRegion }}</li>
							<li>{{ this.order.deliveryCountry }}</li>
							<li>{{ this.order.deliveryPostCode }}</li>
							<li>{{ this.order.deliveryPhone }}</li>
							<li>{{ this.order.deliveryEmail }}</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'order',
		'invoice',
	],
}
</script>