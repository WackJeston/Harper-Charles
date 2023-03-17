<template>
	<h3 @click="this.delivery = !this.delivery">
		Delivery Address
		<i v-if="this.delivery" class="fa-solid fa-angle-up"></i>
		<i v-else class="fa-solid fa-angle-down"></i>
	</h3>

	<form @submit.prevent="deliveryAdd" enctype="multipart/form-data"
		:style="[!this.delivery ? { maxHeight: '0px', padding: '0px 20px' } : { maxHeight: '1000px', padding: '20px' }]">
		<input type="hidden" name="_token" :value="csrf">

		<div class="wb-row">
			<div class="input-label-container">
				<label for="firstname">First Name<span> *</span></label>
				<input type="text" name="firstname" required>
			</div>

			<div class="input-label-container">
				<label for="lastname">Last Name<span> *</span></label>
				<input type="text" name="lastname" required>
			</div>
		</div>

		<label for="line1">Address Line 1<span> *</span></label>
		<input type="text" name="line1" required>

		<div class="wb-row">
			<div class="input-label-container">
				<label for="line2">Address Line 2</label>
				<input type="text" name="line2">
			</div>

			<div class="input-label-container">
				<label for="line3">Address Line 3</label>
				<input type="text" name="line3">
			</div>
		</div>

		<div class="wb-row">
			<div class="input-label-container">
				<label for="city">City / Town<span> *</span></label>
				<input type="text" name="city" required>
			</div>

			<div class="input-label-container">
				<label for="region">County</label>
				<input type="text" name="region">
			</div>
		</div>

		<div class="wb-row">
			<div class="input-label-container">
				<label for="country">Country<span> *</span></label>
				<input type="text" name="country" required>
			</div>

			<div class="input-label-container">
				<label for="postcode">Postcode<span> *</span></label>
				<input type="text" name="postcode" required>
			</div>
		</div>

		<label for="phone">Phone<span> *</span></label>
		<input type="tel" name="phone" required
			pattern="(\s*\(?0\d{4}\)?(\s*|-)\d{3}(\s*|-)\d{3}\s*)|(\s*\(?0\d{3}\)?(\s*|-)\d{3}(\s*|-)\d{4}\s*)|(\s*(7|8)(\d{7}|\d{3}(\-|\s{1})\d{4})\s*)">

		<div class="checkbox-container">
			<input type="checkbox" name="defaultdelivery">
			<label for="defaultdelivery">Make this your default delivery address.</label>
		</div>

		<input class="submit" type="submit" name="submit" value="Save">
	</form>

	<h3 @click="this.billing = !this.billing">
		Billing Address
		<i v-if="this.billing" class="fa-solid fa-angle-up"></i>
		<i v-else class="fa-solid fa-angle-down"></i>
	</h3>

	<form @submit.prevent="billingAdd" enctype="multipart/form-data" class="last-child"
		:style="[!this.billing ? { maxHeight: '0px', padding: '0px 20px' } : { maxHeight: '1000px', padding: '20px' }]">
		<input type="hidden" name="_token" :value="csrf">

		<div class="wb-row">
			<div class="input-label-container">
				<label for="firstname">First Name<span> *</span></label>
				<input type="text" name="firstname" required>
			</div>

			<div class="input-label-container">
				<label for="lastname">Last Name<span> *</span></label>
				<input type="text" name="lastname" required>
			</div>
		</div>

		<label for="line1">Address Line 1<span> *</span></label>
		<input type="text" name="line1" required>

		<div class="wb-row">
			<div class="input-label-container">
				<label for="line2">Address Line 2</label>
				<input type="text" name="line2">
			</div>

			<div class="input-label-container">
				<label for="line3">Address Line 3</label>
				<input type="text" name="line3">
			</div>
		</div>

		<div class="wb-row">
			<div class="input-label-container">
				<label for="city">City / Town<span> *</span></label>
				<input type="text" name="city" required>
			</div>

			<div class="input-label-container">
				<label for="region">County</label>
				<input type="text" name="region">
			</div>
		</div>

		<div class="wb-row">
			<div class="input-label-container">
				<label for="country">Country<span> *</span></label>
				<input type="text" name="country" required>
			</div>

			<div class="input-label-container">
				<label for="postcode">Postcode<span> *</span></label>
				<input type="text" name="postcode" required>
			</div>
		</div>

		<label for="phone">Phone<span> *</span></label>
		<input type="tel" name="phone" required
			pattern="(\s*\(?0\d{4}\)?(\s*|-)\d{3}(\s*|-)\d{3}\s*)|(\s*\(?0\d{3}\)?(\s*|-)\d{3}(\s*|-)\d{4}\s*)|(\s*(7|8)(\d{7}|\d{3}(\-|\s{1})\d{4})\s*)">

		<div class="checkbox-container">
			<input type="checkbox" name="defaultbilling">
			<label for="defaultbilling">Make this your default billing address.</label>
		</div>

		<input class="submit" type="submit" name="submit" value="Save">
	</form>
</template>

<script>
export default {
	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			delivery: true,
			billing: false,
		}
	},

	methods: {
		async deliveryAdd(submitEvent) {
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

				console.log(this.values);

				this.result = await this.$http.post(
					'/checkoutAddAddress/delivery/' + this.values,
					{ name: "delivery-add" }
				);
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
			} finally {
				console.log('----SUCCESS----');
				console.log(this.result);
				// if (this.result.data['success']) {
				// 	this.cartAlert('Item added to cart.');
				// } else {
				// 	window.location.href = '/loginCart';
				// }
			}
		},

	},
}
</script>