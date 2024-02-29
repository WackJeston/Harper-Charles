<template>
	<div class="web-box section-width">
		<h3 id="delivery-header">
			<i class="fa-solid fa-house-chimney"></i>
			Delivery Address
			<p></p>
		</h3>

		<div id="delivery-container" class="checkout-container">
			<div class="saved-records-container">
				<div v-for="(address, i) in this.deliveryaddresses" class="saved-record" :id="'address-' + address.id">
					<ul>
						<li>{{ address.firstName }} {{ address.lastName }}</li>
						<li>{{ address.company }}</li>
						<li>{{ address.line1 }}</li>
						<li>{{ address.city }}, {{ address.region }}</li>
						<li>{{ address.country }}</li>
						<li>{{ address.postCode }}</li>
						<li>{{ address.phone }}</li>
						<li>{{ address.email }}</li>
					</ul>

					<div class="record-buttons">
						<button @click="this.checkoutContinue(address.id)" id="continue" class="page-button padding">
							Deliver to this address
							<!-- <i class="fa-solid fa-angles-right"></i> -->
						</button>

						<span v-if="address.defaultShipping" class="default-record">Default Address</span>
						<button v-else class="record-button">Set as default address</button>

						<button @click="this.deleteAddress('delivery', address.id)" class="record-button delete-record">Remove <i class="fa-solid fa-xmark"></i></button>

						<!-- <i @click.stop="this.defaultAddress('delivery', address.id)" class="fa-solid fa-square popup-label-button">
							<i class="fa-solid fa-star" :class="[address.defaultShipping == 1 ? 'star-selected' : '']"></i>
							<div class="popup-label-container">
								<span class="popup-label">Make Default</span>
							</div>
						</i> -->
					</div>
				</div>
			</div>

			<div class="record-toggle-container">
				<button v-if="this.deliveryaddresses.length > 0" class="record-toggle page-button" @click="this.deliveryForm = !this.deliveryForm">
					New Address
					<i v-if="this.deliveryForm" class="fa-solid fa-angle-up"></i>
					<i v-else class="fa-solid fa-angle-down"></i>
				</button>
			</div>

			<form @submit.prevent="this.addressAdd($event, 'delivery')" enctype="multipart/form-data" :style="[(this.deliveryForm == true || this.deliveryaddresses.length == 0) ? { maxHeight: '1000px' } : { maxHeight: '0px' }]">
				<input type="hidden" name="_token" :value="csrf">

				<div :style="[this.deliveryaddresses.length > 0 ? { marginTop: '20px' } : { marginTop: '0px' }]" class="wb-row">
					<div class="input-label-container">
						<label for="firstname">First Name<span class="red"> *</span></label>
						<input type="text" name="firstname" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="lastname">Last Name<span class="red"> *</span></label>
						<input type="text" name="lastname" required maxlength="100">
					</div>
				</div>

				<label for="company">Company</label>
				<input type="text" name="company" maxlength="100">

				<label for="line1">Address Line 1<span class="red"> *</span></label>
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
						<label for="city">City / Town<span class="red"> *</span></label>
						<input type="text" name="city" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="region">County</label>
						<input type="text" name="region" maxlength="100">
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="postcode">Postcode<span class="red"> *</span></label>
						<input type="text" name="postcode" required maxlength="50">
					</div>

					<div class="input-label-container">
						<label for="country">Country<span class="red"> *</span></label>
						<select type="text" name="country" required maxlength="100">
							<option value="">Select Country</option>
							<option v-for="country in this.countries" :value="country.code">{{ country.name }}</option>
						</select>
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="phone">Phone<span class="red"> *</span></label>
						<input type="tel" name="phone" required maxlength="20">
					</div>

					<div class="input-label-container">
						<label for="email">email<span class="red"> *</span></label>
						<input type="email" name="email" required maxlength="100">
					</div>
				</div>

				<div class="checkbox-container">
					<input type="checkbox" name="defaultdelivery">
					<label for="defaultdelivery">Make this your default delivery address.</label>
				</div>

				<input id="billingMarker" class="submit" type="submit" name="submit" value="Save">
			</form>
		</div>

		<!-- <div class="web-box section-width">
			<h3 id="billing-header">
				<i class="fa-solid fa-file-invoice-dollar"></i>
				Billing Address
				<p></p>
			</h3>

			<div id="billing-container" class="checkout-container">
				<div class="saved-records-container">
				<ul v-for="(address, i) in this.billingaddresses" class="saved-record" :id="'address-' + address.id"
					:class="[address.defaultBilling == 1 ? 'selected-record' : '']"
					@click.stop="this.selectAddress($event, 'billing', address.id)">
					<li>{{ address.firstName }} {{ address.lastName }}</li>
					<li>{{ address.company }}</li>
					<li>{{ address.line1 }}</li>
					<li>{{ address.city }}, {{ address.region }}</li>
					<li>{{ address.country }}</li>
					<li>{{ address.postCode }}</li>
					<li>{{ address.phone }}</li>
					<li>{{ address.email }}</li>
					<div class="record-buttons">
						<i @click.stop="this.deleteAddress('billing', address.id)" class="fa-solid fa-square-xmark popup-label-button">
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
					</div>
					<i v-if="address.defaultBilling == 1" class="fa-regular fa-circle-check"></i>
				</ul>
			</div>

			<div class="record-toggle-container">
				<button v-if="this.billingaddresses.length > 0" class="record-toggle page-button" @click="this.billingForm = !this.billingForm">
					New Address
					<i v-if="this.billingForm" class="fa-solid fa-angle-up"></i>
					<i v-else class="fa-solid fa-angle-down"></i>
				</button>
			</div>

			<form @submit.prevent="this.addressAdd($event, 'billing')" enctype="multipart/form-data"
				:style="[(this.billingForm == true || this.billingaddresses.length == 0) ? { maxHeight: '1000px' } : { maxHeight: '0px' }]">
				<input type="hidden" name="_token" :value="csrf">

				<div :style="[this.billingaddresses.length > 0 ? { marginTop: '20px' } : { marginTop: '0px' }]" class="wb-row">
					<div class="input-label-container">
						<label for="firstname">First Name<span class="red"> *</span></label>
						<input type="text" name="firstname" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="lastname">Last Name<span class="red"> *</span></label>
						<input type="text" name="lastname" required maxlength="100">
					</div>
				</div>

				<label for="company">Company</label>
				<input type="text" name="company" maxlength="100">

				<label for="line1">Address Line 1<span class="red"> *</span></label>
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
						<label for="city">City / Town<span class="red"> *</span></label>
						<input type="text" name="city" required maxlength="100">
					</div>

					<div class="input-label-container">
						<label for="region">County</label>
						<input type="text" name="region" maxlength="100">
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="postcode">Postcode<span class="red"> *</span></label>
						<input type="text" name="postcode" required maxlength="50">
					</div>

					<div class="input-label-container">
						<label for="country">Country<span class="red"> *</span></label>
						<select type="text" name="country" required maxlength="100">
							<option value="">Select Country</option>
							<option v-for="country in this.countries" :value="country.code">{{ country.name }}</option>
						</select>
					</div>
				</div>

				<div class="wb-row">
					<div class="input-label-container">
						<label for="phone">Phone<span class="red"> *</span></label>
						<input type="tel" name="phone" required maxlength="20">
					</div>

					<div class="input-label-container">
						<label for="email">email<span class="red"> *</span></label>
						<input type="email" name="email" required maxlength="100">
					</div>
				</div>

				<div class="checkbox-container">
					<input type="checkbox" name="defaultbilling">
					<label for="defaultbilling">Make this your default billing address.</label>
				</div>

				<input id="billingMarker" class="submit" type="submit" name="submit" value="Save">
			</form>
		</div> -->
	</div>

	<!-- <div class="checkout-button-container section-width">
		<button @click="this.checkoutContinue()" id="continue" class="page-button padding">
			Payment Method
			<i class="fa-solid fa-angles-right"></i>
		</button>
	</div> -->
</template>

<script>
export default {
	props: [
		'deliveryaddressespre',
		'defaultdelivery',
		'billingaddressespre',
		'defaultbilling',
		'countries',
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

	methods: {
		checkoutContinue() {
			if (this.deliverySelected != 0 && this.billingSelected != 0) {
				window.location.href = '/checkoutContinueAddresses/' + this.deliverySelected + '/' + this.billingSelected;

			} else {
				if (this.billingSelected == 0) {
					this.errorMessage('billing');
				} else {
					this.errorMessage('billing', false);
				}

				if (this.deliverySelected == 0) {
					this.errorMessage('delivery');
				} else {
					this.errorMessage('delivery', false);
				}
			}
		},

		errorMessage(type, toggle = true) {
			let header = document.querySelector('#' + type + '-header');
			let errorMessage = document.querySelector('#' + type + '-header p');

			if (toggle == true) {
				window.location.href = '#' + type + 'Marker';

				header.style.backgroundColor = '#FF6666';
				errorMessage.innerHTML = 'Please select a ' + type + ' address.';

			} else {
				header.style.backgroundColor = '#5E6264';
				errorMessage.innerHTML = '';
			}

		},

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

				let previousAddress = document.querySelector('#' + type + '-container .selected-record');
				if (previousAddress != null) {
					previousAddress.classList.remove('selected-record');
				}

				let newAddress = document.querySelector('#' + type + '-container #address-' + id);
				newAddress.classList.add('selected-record');
				let innerAddress = newAddress.innerHTML + '<i class="fa-regular fa-circle-check"></i>';
				newAddress.innerHTML = innerAddress;

				if (type == 'delivery') {
					this.deliverySelected = id;
				} else if (type == 'billing') {
					this.billingSelected = id;
				}
				
				this.errorMessage(type, false);

			} else if (submitEvent.target.className == buttons[0]) {
				this.deleteAddress(type, id);

			} else if (submitEvent.target.className == buttons[1] || submitEvent.target.className == buttons[2]) {
				this.defaultAddress(type, id);
			}
		},

		deleteAddress(type, id) {
			const warningZone = document.querySelector('.warning-overlay');
			const message = document.querySelector('.warning-overlay p');
			const deleteLink = document.querySelector('.warning-overlay #delete-link');

			message.innerHTML = 'This address will be permanently deleted. Are you sure?';
			deleteLink.addEventListener('click', () => {
				this.deleteAddress2(type, id);
				warningZone.style.display = 'none';
			});

			warningZone.style.display = 'flex';
		},

		async deleteAddress2(type, id) {
			try {
				this.response = await fetch("/checkoutDeleteAddress/" + id);
				this.result = await this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				if (this.result) {
					if (type == 'delivery') {
						this.deliverySelected = 0;

						this.deliveryaddresses.forEach(address => {
							if (address.id == id) {
								let index = this.deliveryaddresses.indexOf(address);
								this.deliveryaddresses.splice(index, 1);
							}
						});

					} else if (type == 'billing') {
						this.billingSelected = 0;

						this.billingaddresses.forEach(address => {
							if (address.id == id) {
								let index = this.billingaddresses.indexOf(address);
								this.billingaddresses.splice(index, 1);
							}
						});
					}
				}
			}
		},

		async defaultAddress(type, id) {
			try {
				this.response = await fetch("/checkoutDefaultAddress/" + type + '/' + id);
				this.result = await this.response.json();
				
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
			let values = [];

			for (var i = 1; i < submitEvent.target.length; i++) {
				let name = submitEvent.target[i].name;
				let value = null;

				if (submitEvent.target[i].type == 'checkbox') {
					value = submitEvent.target[i].checked;

				} else if (submitEvent.target[i].name != 'submit') {
					value = submitEvent.target[i].value;
				}

				values.push([name, value]);
			}

			values = JSON.stringify(values);

			try {
				this.response = await fetch("/checkoutAddAddress/" + type + '/' + values);
				this.result = await this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				if (type == 'delivery') {
					this.deliveryaddresses.push(this.result);
					this.deliveryForm = false;

				} else if (type == 'billing') {
					this.billingaddresses.push(this.result);
					this.billingForm = false;
				}

				let form = document.querySelector('#' + type + '-container form');
				form.reset();
			}
		},
	},
}
</script>