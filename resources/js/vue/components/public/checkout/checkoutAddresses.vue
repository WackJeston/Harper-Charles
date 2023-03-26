<template>
	<h3 @click="this.delivery = !this.delivery">
		<i class="fa-solid fa-house-chimney"></i>
		Delivery Address
		<i v-if="this.delivery" class="fa-solid fa-angle-up"></i>
		<i v-else class="fa-solid fa-angle-down"></i>
	</h3>

	<div id="delivery-container"
		:style="[!this.delivery ? { maxHeight: '0px', padding: '0px 20px' } : { maxHeight: '2000px', padding: '20px' }]">
		<div class="saved-addresses">
			<ul v-for="(address, i) in this.deliveryaddresses" class="saved-address" :id="'address-' + address.id"
				:class="[address.defaultShipping == 1 ? 'selected-address' : '']"
				@click="this.selectAddress('delivery', address.id)">
				<li>{{ address.firstName }} {{ address.lastName }}</li>
				<li>{{ address.line1 }}</li>
				<li>{{ address.city }}, {{ address.region }}</li>
				<li>{{ address.country }}</li>
				<li>{{ address.postCode }}</li>
				<li>{{ address.phone }}</li>
				<i @click="this.deleteAddress(address.id)" .stop="this.selectAddress"
					class="fa-solid fa-square-xmark popup-label-button">
					<div class="popup-label-container">
						<span class="popup-label">Delete Address</span>
					</div>
				</i>
				<i @click="this.defaultAddress('delivery', address.id)" @click.stop="this.selectAddress"
					class="fa-solid fa-square-check popup-label-button">
					<div class="popup-label-container">
						<span class="popup-label">Make Default</span>
					</div>
				</i>
				<i v-if="address.defaultShipping == 1" class="fa-regular fa-circle-check"></i>
			</ul>
		</div>

		<form @submit.prevent="this.addressAdd($event, 'delivery')" enctype="multipart/form-data">
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
				<input type="checkbox" name="defaultdelivery">
				<label for="defaultdelivery">Make this your default delivery address.</label>
			</div>

			<input class="submit" type="submit" name="submit" value="Save">
		</form>
	</div>

	<h3 @click="this.billing = !this.billing">
		<i class="fa-solid fa-building-columns"></i>
		Billing Address
		<i v-if="this.billing" class="fa-solid fa-angle-up"></i>
		<i v-else class="fa-solid fa-angle-down"></i>
	</h3>

	<div id="billing-container"
		:style="[!this.billing ? { maxHeight: '0px', padding: '0px 20px' } : { maxHeight: '2000px', padding: '20px' }]">
		<div class="saved-addresses">
			<ul v-for="(address, i) in this.billingaddresses" class="saved-address" :id="'address-' + address.id"
				:class="[address.defaultBilling == 1 ? 'selected-address' : '']"
				@click="this.selectAddress('billing', address.id)">
				<li>{{ address.firstName }} {{ address.lastName }}</li>
				<li>{{ address.line1 }}</li>
				<li>{{ address.city }}, {{ address.region }}</li>
				<li>{{ address.country }}</li>
				<li>{{ address.postCode }}</li>
				<li>{{ address.phone }}</li>
				<i @click="this.deleteAddress(address.id)" class="fa-solid fa-square-xmark popup-label-button">
					<div class="popup-label-container">
						<span class="popup-label">Delete Address</span>
					</div>
				</i>
				<i @click="this.defaultAddress('billing', address.id)" class="fa-solid fa-square-check popup-label-button">
					<div class="popup-label-container">
						<span class="popup-label">Make Default</span>
					</div>
				</i>
				<i v-if="address.defaultBilling == 1" class="fa-regular fa-circle-check"></i>
			</ul>
		</div>

		<form @submit.prevent="this.addressAdd($event, 'billing')" enctype="multipart/form-data">
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
</template>

<script>
export default {
	props: [
		'deliveryaddresses',
		'defaultdelivery',
		'billingaddresses',
		'defaultbilling',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			delivery: true,
			deliverySelected: this.defaultdelivery,
			billing: true,
			billingSelected: this.defaultbilling,
		}
	},

	methods: {
		selectAddress(type, id) {
			let previousTick = document.querySelector('#' + type + '-container .selected-address .fa-circle-check');
			if (previousTick != null) {
				previousTick.remove();
			}
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
		},

		addCustomListener(type, id) {
			console.log('addCustomListener');
			let button = document.querySelector('#address-' + id + ' .fa-square-' + type);

			button.addEventListener('onclick', this.deleteAddress(id));
			// button.addEventListener('onclick', (id2 = id) => { this.deleteAddress(id2); });
			// button.addEventListener('click', 'this.deleteAddress(' + id + ')');

			console.log('addCustomListener complete');
		},

		async deleteAddress(id) {
			try {
				console.log('deleteAddress');

				this.result = await this.$http.post(
					'/checkoutDeleteAddress/' + id,
					{ name: "delete-address" }
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				if (this.result.data == true) {
					let address = document.querySelector('#address-' + id);
					address.remove();
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

				let addressHtml = document.createElement('ul');
				addressHtml.setAttribute('class', 'saved-address');
				addressHtml.setAttribute('id', 'address-' + this.result.data.id);

				let tempHtml = document.createElement('li');
				tempHtml.innerHTML = this.result.data.firstName + ' ' + this.result.data.lastName;
				addressHtml.appendChild(tempHtml);
				tempHtml = document.createElement('li');
				tempHtml.innerHTML = this.result.data.line1;
				addressHtml.appendChild(tempHtml);
				tempHtml = document.createElement('li');
				tempHtml.innerHTML = this.result.data.city + (this.result.data.region ? ', ' + this.result.data.region : '');
				addressHtml.appendChild(tempHtml);
				tempHtml = document.createElement('li');
				tempHtml.innerHTML = this.result.data.country;
				addressHtml.appendChild(tempHtml);
				tempHtml = document.createElement('li');
				tempHtml.innerHTML = this.result.data.postCode;
				addressHtml.appendChild(tempHtml);
				tempHtml = document.createElement('li');
				tempHtml.innerHTML = this.result.data.phone;
				addressHtml.appendChild(tempHtml);

				let innerAddressHtml1 = document.createTextNode('Delete Address');
				let innerAddressHtml2 = document.createElement('span');
				innerAddressHtml2.setAttribute('class', 'popup-label');
				innerAddressHtml2.appendChild(innerAddressHtml1);
				let innerAddressHtml3 = document.createElement('div');
				innerAddressHtml3.setAttribute('class', 'popup-label-container');
				innerAddressHtml3.appendChild(innerAddressHtml2);
				let innerAddressHtml4 = document.createElement('i');
				innerAddressHtml4.setAttribute('class', 'fa-solid fa-square-xmark popup-label-button');
				// innerAddressHtml4.setAttribute('onclick', (id = this.result.data.id) => { this.deleteAddress(id); });
				// innerAddressHtml4.onclick = function (id = this.result.data.id) { this.deleteAddress(id); };
				// innerAddressHtml4.onclick = 'deleteAddress(' + this.result.data.id + ')';
				// innerAddressHtml4.setAttribute('onclick', 'deleteAddress(' + this.result.data.id + ')');
				// innerAddressHtml4.setAttribute('onclick', 'deleteAddress(' + this.result.data.id + ')');
				// innerAddressHtml4.addEventListener('click', function (id = this.result.data.id) { this.deleteAddress(id); });
				innerAddressHtml4.appendChild(innerAddressHtml3);

				addressHtml.appendChild(innerAddressHtml4);

				innerAddressHtml1 = document.createTextNode('Make Default');
				innerAddressHtml2 = document.createElement('span');
				innerAddressHtml2.setAttribute('class', 'popup-label');
				innerAddressHtml2.appendChild(innerAddressHtml1);
				innerAddressHtml3 = document.createElement('div');
				innerAddressHtml3.setAttribute('class', 'popup-label-container');
				innerAddressHtml3.appendChild(innerAddressHtml2);
				innerAddressHtml4 = document.createElement('i');
				innerAddressHtml4.setAttribute('class', 'fa-solid fa-square-check popup-label-button');
				innerAddressHtml4.appendChild(innerAddressHtml3);

				addressHtml.appendChild(innerAddressHtml4);

				let addresses = document.querySelector('#delivery-container .saved-addresses');
				addresses.appendChild(addressHtml);

				console.log('add address');

				this.addCustomListener('xmark', this.result.data.id);

				this.selectAddress(type, this.result.data.id);
			}
		},
	},
}
</script>