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
						<td class="align-right">£{{ this.order.total }}</td>
					</tr>
				</tfoot>
			</table>

			<div class="saved-records-columns cols-2">
				<div class="saved-records-container">
					<div class="saved-record">
						<ul>
							<li class="billing-address">Billing Address</li>
							<li class="first">{{ this.order.billingAddress.firstName }} {{ this.order.billingAddress.lastName }}</li>
							<li>{{ this.order.billingAddress.company }}</li>
							<li>{{ this.order.billingAddress.line1 }}</li>
							<li>{{ this.order.billingAddress.city }}, {{ this.order.billingAddress.region }}</li>
							<li>{{ this.order.billingAddress.country }}</li>
							<li>{{ this.order.billingAddress.postCode }}</li>
							<li>{{ this.order.billingAddress.phone }}</li>
							<li>{{ this.order.billingAddress.email }}</li>
						</ul>
					</div>
				</div>

				<div class="saved-records-container">
					<div class="saved-record">
						<ul>
							<li class="billing-address">Delivery Address</li>
							<li class="first">{{ this.order.deliveryAddress.firstName }} {{ this.order.deliveryAddress.lastName }}</li>
							<li>{{ this.order.deliveryAddress.company }}</li>
							<li>{{ this.order.deliveryAddress.line1 }}</li>
							<li>{{ this.order.deliveryAddress.city }}, {{ this.order.deliveryAddress.region }}</li>
							<li>{{ this.order.deliveryAddress.country }}</li>
							<li>{{ this.order.deliveryAddress.postCode }}</li>
							<li>{{ this.order.deliveryAddress.phone }}</li>
							<li>{{ this.order.deliveryAddress.email }}</li>
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