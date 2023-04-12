<template>
	<div class="web-box">
		<h3>
			<i class="fa-solid fa-house-chimney"></i>
			Delivery Address
		</h3>

		<div id="delivery-container">
			<div class="saved-addresses">
				<ul v-for="(address, i) in this.deliveryaddresses" class="saved-address" :id="'address-' + address.id"
					:class="[address.defaultShipping == 1 ? 'selected-address' : '']"
					@click.stop="this.selectAddress($event, 'delivery', address.id)">
					<li>{{ address.firstName }} {{ address.lastName }}</li>
					<li>{{ address.line1 }}</li>
					<li>{{ address.city }}, {{ address.region }}</li>
					<li>{{ address.country }}</li>
					<li>{{ address.postCode }}</li>
					<li>{{ address.phone }}</li>
					<i @click.stop="this.deleteAddress(address.id)" class="fa-solid fa-square-xmark popup-label-button">
						<div class="popup-label-container">
							<span class="popup-label">Delete Address</span>
						</div>
					</i>
					<i @click.stop="this.defaultAddress('delivery', address.id)" class="fa-solid fa-square popup-label-button">
						<i class="fa-solid fa-star" :class="[address.defaultShipping == 1 ? 'star-selected' : '']"></i>
						<div class="popup-label-container">
							<span class="popup-label">Make Default</span>
						</div>
					</i>
					<i v-if="address.defaultShipping == 1" class="fa-regular fa-circle-check"></i>
				</ul>
			</div>

			<button v-if="this.deliveryaddresses.length > 0" class="address-toggle page-button"
				@click="this.deliveryForm = !this.deliveryForm">
				<i v-if="this.deliveryForm" class="fa-solid fa-angle-up"></i>
				<i v-else class="fa-solid fa-angle-down"></i>
				Add New Address
			</button>

			<form @submit.prevent="this.addressAdd($event, 'delivery')" enctype="multipart/form-data"
				:style="[this.deliveryForm == false ? { maxHeight: '0px' } : { maxHeight: '1000px' }]">
				<input type="hidden" name="_token" :value="csrf">

				<div class="wb-row first-child">
					<div class="input-label-container">
						<label for="firstname">First Name<span> *</span></label>
						<input type="text" name="firstname" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="lastname">Last Name<span> *</span></label>
						<input type="text" name="lastname" required maxlength="100">
					</div>
				</div>

				<label for="company">Company</label>
				<input type="text" name="company" maxlength="100">

				<label for="line1">Address Line 1<span> *</span></label>
				<input type="text" name="line1" required maxlength="200">

				<div class="wb-row">
					<div class="input-label-container">
						<label for="line2">Address Line 2</label>
						<input type="text" name="line2" maxlength="200">
					</div>

					<div class="input-label-container">
						<label for="line3">Address Line 3</label>
						<input type="text" name="line3" maxlength="200">
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="city">City / Town<span> *</span></label>
						<input type="text" name="city" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="region">County</label>
						<input type="text" name="region" maxlength="100">
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="country">Country<span> *</span></label>
						<input type="text" name="country" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="postcode">Postcode<span> *</span></label>
						<input type="text" name="postcode" required maxlength="50">
					</div>
				</div>

				<label for="phone">Phone<span> *</span></label>
				<input type="tel" name="phone" required maxlength="20">

				<div class="checkbox-container">
					<input type="checkbox" name="defaultdelivery">
					<label for="defaultdelivery">Make this your default delivery address.</label>
				</div>

				<input class="submit" type="submit" name="submit" value="Save">
			</form>
		</div>
	</div>

	<div class="web-box">
		<h3>
			<i class="fa-solid fa-house-chimney"></i>
			Billing Address
		</h3>

		<div id="billing-container">
			<div class="saved-addresses">
				<ul v-for="(address, i) in this.billingaddresses" class="saved-address" :id="'address-' + address.id"
					:class="[address.defaultBilling == 1 ? 'selected-address' : '']"
					@click.stop="this.selectAddress($event, 'billing', address.id)">
					<li>{{ address.firstName }} {{ address.lastName }}</li>
					<li>{{ address.line1 }}</li>
					<li>{{ address.city }}, {{ address.region }}</li>
					<li>{{ address.country }}</li>
					<li>{{ address.postCode }}</li>
					<li>{{ address.phone }}</li>
					<i @click.stop="this.deleteAddress(address.id)" class="fa-solid fa-square-xmark popup-label-button">
						<div class="popup-label-container">
							<span class="popup-label">Delete Address</span>
						</div>
					</i>
					<i @click.stop="this.defaultAddress('billing', address.id)" class="fa-solid fa-square popup-label-button">
						<i class="fa-solid fa-star" :class="[address.defaultBilling == 1 ? 'star-selected' : '']"></i>
						<div class="popup-label-container">
							<span class="popup-label">Make Default</span>
						</div>
					</i>
					<i v-if="address.defaultBilling == 1" class="fa-regular fa-circle-check"></i>
				</ul>
			</div>

			<button class="address-toggle page-button" @click="this.billingForm = !this.billingForm">
				Add New Address
			</button>

			<form @submit.prevent="this.addressAdd($event, 'billing')" enctype="multipart/form-data"
				:style="[!this.deliveryForm ? { maxHeight: '0px' } : { maxHeight: '1000px' }]">
				<input type="hidden" name="_token" :value="csrf">

				<div class="wb-row">
					<div class="input-label-container">
						<label for="firstname">First Name<span> *</span></label>
						<input type="text" name="firstname" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="lastname">Last Name<span> *</span></label>
						<input type="text" name="lastname" required maxlength="100">
					</div>
				</div>

				<label for="company">Company</label>
				<input type="text" name="company" maxlength="100">

				<label for="line1">Address Line 1<span> *</span></label>
				<input type="text" name="line1" required maxlength="200">

				<div class="wb-row">
					<div class="input-label-container">
						<label for="line2">Address Line 2</label>
						<input type="text" name="line2" maxlength="200">
					</div>

					<div class="input-label-container">
						<label for="line3">Address Line 3</label>
						<input type="text" name="line3" maxlength="200">
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="city">City / Town<span> *</span></label>
						<input type="text" name="city" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="region">County</label>
						<input type="text" name="region" maxlength="100">
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="country">Country<span> *</span></label>
						<input type="text" name="country" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="postcode">Postcode<span> *</span></label>
						<input type="text" name="postcode" required maxlength="50">
					</div>
				</div>

				<label for="phone">Phone<span> *</span></label>
				<input type="tel" name="phone" required maxlength="20">

				<div class="checkbox-container">
					<input type="checkbox" name="defaultbilling">
					<label for="defaultbilling">Make this your default billing address.</label>
				</div>

				<input class="submit" type="submit" name="submit" value="Save">
			</form>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'deliveryaddressespre',
		'defaultdelivery',
		'billingaddressespre',
		'defaultbilling',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

			deliveryaddresses: this.deliveryaddressespre,
			deliverySelected: this.defaultdelivery,
			deliveryForm: this.deliveryaddressespre.length > 0 ? false : true,

			billingaddresses: this.billingaddressespre,
			billingSelected: this.defaultbilling,
			billingForm: this.billingaddressespre.length > 0 ? false : true,
		}
	},

	mounted() {
		console.log(this.deliveryaddresses);
	},

	methods: {
		selectAddress(submitEvent, type, id) {
			const buttons = [
				'fa-solid fa-square-xmark popup-label-button',
				'fa-solid fa-square popup-label-button',
				'fa-solid fa-star'
			];

			if (submitEvent == null || !buttons.includes(submitEvent.target.className)) {
				let previousTicks = document.querySelectorAll('#' + type + '-container .fa-circle-check');
				previousTicks.forEach(tick => {
					tick.remove();
				});

				let previousAddress = document.querySelector('#' + type + '-container .selected-address');
				if (previousAddress != null) {
					previousAddress.classList.remove('selected-address');
				}

				let newAddress = document.querySelector('#' + type + '-container #address-' + id);
				newAddress.classList.add('selected-address');
				let innerAddress = newAddress.innerHTML + '<i class="fa-regular fa-circle-check"></i>';
				newAddress.innerHTML = innerAddress;

				if (type == 'delivery') {
					this.deliverySelected = id;
				} else if (type == 'billing') {
					this.billingSelected = id;
				}

			} else if (submitEvent.target.className == buttons[0]) {
				this.deleteAddress(id);

			} else if (submitEvent.target.className == buttons[1] || submitEvent.target.className == buttons[2]) {
				this.defaultAddress(type, id);
			}
		},

		async deleteAddress(id) {
			try {
				this.result = await this.$http.post(
					'/checkoutDeleteAddress/' + id,
					{ name: "delete-address" }
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				if (this.result.data == true) {
					// let address = document.querySelector('#address-' + id);
					// address.remove();

					this.deliveryaddresses.forEach(address => {
						if (address.id == id) {
							this.deliveryaddresses.splice(this.deliveryaddresses.indexOf(), 1);
						}
					});
				}
			}
		},

		async defaultAddress(type, id) {
			try {
				this.result = await this.$http.post(
					'/checkoutDefaultAddress/' + type + '/' + id,
					{ name: "default-address" }
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				let oldDefault = document.querySelector('#' + type + '-container .star-selected');
				if (oldDefault != null) {
					oldDefault.classList.remove('star-selected');
				}

				let newDefault = document.querySelector('#' + type + '-container #address-' + id + ' .fa-star');
				newDefault.classList.add('star-selected');
			}
		},

		async addressAdd(submitEvent, type) {
			try {
				this.values = '';

				for (var i = 1; i < submitEvent.target.length; i++) {
					if (submitEvent.target[i].value != '' && submitEvent.target[i].value != null) {
						if (i == 1) {
							this.values += submitEvent.target[i].name + '<=>' + submitEvent.target[i].value;

						} else if (submitEvent.target[i].type == 'checkbox') {
							this.values += '<&>' + submitEvent.target[i].name + '<=>' + submitEvent.target[i].checked;

						} else if (submitEvent.target[i].name != 'submit') {
							this.values += '<&>' + submitEvent.target[i].name + '<=>' + submitEvent.target[i].value;
						}
					}
				}

				this.result = await this.$http.post(
					'/checkoutAddAddress/' + type + '/' + this.values,
					{ name: "delivery-add" }
				);

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);

			} finally {
				if (type == 'delivery') {
					this.deliveryaddresses.push(this.result.data);
				} else if (type == 'billing') {
					this.billingaddresses.push(this.result.data);
				}

				setTimeout(() => {
					this.selectAddress(null, type, this.result.data.id);

					if (this.result.data.defaultShipping == 1 || this.result.data.defaultBilling == 1) {
						this.defaultAddress(type, this.result.data.id);
					}
				}, 10);
			}
		},
	},
}
</script>