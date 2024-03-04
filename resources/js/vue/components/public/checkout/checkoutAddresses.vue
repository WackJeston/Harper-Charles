<template>
	<div class="web-box section-width">
		<h3 v-if="this.addresses.length > 0" id="record-header">
			<!-- <i class="fa-solid fa-house-chimney"></i> -->
			Please select your delivery address
			<p></p>
		</h3>

		<h3 v-else id="record-header">
			<!-- <i class="fa-solid fa-house-chimney"></i> -->
			Please enter your billing address
			<p></p>
		</h3>

		<div id="address-container" class="checkout-container">
			<div class="saved-records-container">
				<div v-for="(address, i) in this.addresses" :class="{ 'saved-record-billing-address' : address.defaultBilling }" class="saved-record" :id="'address-' + address.id">
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
						<div class="record-button-container">
							<button @click="this.checkoutContinue(address.id)" id="continue" class="page-button padding">
								Deliver to this address
								<i class="fa-solid fa-angles-right"></i>
							</button>
						</div>

						<div v-show="!address.defaultBilling" class="record-button-container">
							<button @click="this.deleteAddress(address.id)" class="record-button delete-record">Remove <i class="fa-solid fa-xmark"></i></button>
						</div>
						<div class="record-button-container">
							<button @click="this.editAddress(address.id)" class="record-button">Edit <i class="fa-solid fa-pen-to-square"></i></button>
						</div>
						<div v-show="!address.defaultBilling" class="record-button-container">
							<button @click="this.setBillingAddress(address.id)" class="record-button">Use as billing address</button>
						</div>

						<div v-show="address.defaultBilling" class="record-button-container">
							<span class="billing-address">Billing Address</span>
						</div>
					</div>
				</div>
			</div>

			<button v-if="this.addresses.length > 0" class="record-toggle page-button padding no-margin" @click="this.toggleForm()">
				New Address
				<i v-if="this.showForm" class="fa-solid fa-angle-up"></i>
				<i v-else class="fa-solid fa-angle-down"></i>
			</button>

			<form @submit.prevent="this.addressAdd($event)" enctype="multipart/form-data" id="#addressForm" :style="[(this.showForm == true || this.addresses.length == 0) ? { maxHeight: '1000px' } : { maxHeight: '0px' }]">
				<input type="hidden" name="_token" :value="csrf">
				<input type="hidden" name="update">

				<div :style="[this.addresses.length > 0 ? { marginTop: '20px' } : { marginTop: '0px' }]" class="wb-row">
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

				<label for="phone">Phone<span class="red"> *</span></label>
				<input type="tel" name="phone" required maxlength="20">

				<label for="email">email<span class="red"> *</span></label>
				<input type="email" name="email" required maxlength="100">

				<div v-show="this.addresses.length > 0 && !this.billingAddressEdit" class="checkbox-container">
					<input type="checkbox" name="defaultbilling">
					<label for="defaultbilling">Make this your default billing address.</label>
				</div>

				<input class="submit page-button padding" type="submit" name="submit" value="Save">
			</form>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'addressespre',
		'countries',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

			addresses: this.addressespre,
			showForm: this.addressespre.length > 0 ? false : true,
			billingAddressEdit: false,
		}
	},

	methods: {
		toggleForm() {
			this.showForm = !this.showForm;

			let form = document.querySelector('#address-container form');

			if (!this.showForm) {
				setTimeout(() => {
					this.billingAddressEdit = false;
					form.update.value = null;
					form.reset();
				}, 300);
			}
		},

		deleteAddress(id) {
			const warningZone = document.querySelector('.warning-overlay');
			const message = document.querySelector('.warning-overlay p');
			const deleteLink = document.querySelector('.warning-overlay #delete-link');

			message.innerHTML = 'This address will be permanently deleted. Are you sure?';
			deleteLink.addEventListener('click', () => {
				this.deleteAddress2(id);
				warningZone.style.display = 'none';
			}, {once : true});

			warningZone.style.display = 'flex';
		},

		async deleteAddress2(id) {
			try {
				this.response = await fetch("/checkoutDeleteAddress/" + id);
				this.result = await this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				if (this.result) {
					this.addresses.forEach(address => {
						if (address.id == id) {
							let index = this.addresses.indexOf(address);
							this.addresses.splice(index, 1);
						}
					});
				}
			}
		},

		async setBillingAddress(id) {
			try {
				this.response = await fetch("/checkoutSetBillingAddress/" + id);
				this.result = await this.response;
				
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				if (this.result) {
					this.addresses.forEach(address => {
						if (address.defaultBilling) {
							address.defaultBilling = false;
						}

						if (address.id == id) {
							address.defaultBilling = true;
						}
					});
				}
			}
		},

		editAddress(id) {
			let form = document.querySelector('#address-container form');

			this.addresses.forEach(address => {
				if (address.id == id) {
					form.querySelector('input[name="update"]').value = address.id;
					form.firstname.value = address.firstName;
					form.lastname.value = address.lastName;
					form.company.value = address.company;
					form.line1.value = address.line1;
					form.line2.value = address.line2;
					form.line3.value = address.line3;
					form.city.value = address.city;
					form.region.value = address.region;
					form.postcode.value = address.postCode;
					form.country.value = address.countryCode;
					form.phone.value = address.phone;
					form.email.value = address.email;
					form.defaultbilling.checked = address.defaultBilling;

					if (address.defaultBilling) {
						this.billingAddressEdit = true;
					}
				}
			});

			this.showForm = true;

			setTimeout(() => {
				form.scrollIntoView();
			}, 300);
		},

		async addressAdd(submitEvent) {
			let form = document.querySelector('#address-container form');
			var values = [];

			if (this.addresses.length == 0) {
				submitEvent.target.defaultbilling.checked = true;
			}

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
				this.response = await fetch("/checkoutAddAddress/" + values);
				this.result = await this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				if (this.result.data.defaultBilling) {
					this.addresses.forEach(address => {
						if (address.defaultBilling) {
							address.defaultBilling = false;
						}
					});
				}

				if (this.result.updated) {
					this.addresses.forEach(address => {
						if (address.id == this.result.data.id) {
							let index = this.addresses.indexOf(address);
							this.addresses.splice(index, 1, this.result.data);
						}
					});
				
				} else {
					this.addresses.push(this.result.data);
				}

				this.toggleForm();

				setTimeout(() => {
					let scrollElement = document.querySelector('#addres-' + this.result.data.id);

					if (scrollElement) {
						scrollElement.scrollIntoView();
					}

				}, 300);
			}
		},
	},
}
</script>