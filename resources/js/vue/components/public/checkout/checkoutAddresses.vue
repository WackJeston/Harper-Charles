<template>
	<form action="/cart" method="GET" enctype="multipart/form-data">
		<h3>Billing Address</h3>

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

		<label for="city">City / Town<span> *</span></label>
		<input type="text" name="city" required>

		<label for="region">County</label>
		<input type="text" name="region">

		<label for="country">Country<span> *</span></label>
		<input type="text" name="country" required>

		<label for="postcode">Postcode<span> *</span></label>
		<input type="text" name="postcode" required>

		<label for="phone">Phone<span> *</span></label>
		<input type="tel" name="phone" required
			pattern="(\s*\(?0\d{4}\)?(\s*|-)\d{3}(\s*|-)\d{3}\s*)|(\s*\(?0\d{3}\)?(\s*|-)\d{3}(\s*|-)\d{4}\s*)|(\s*(7|8)(\d{7}|\d{3}(\-|\s{1})\d{4})\s*)">

		<div class="checkbox-container">
			<input type="checkbox" name="defaultbilling">
			<label for="defaultbilling">Make this your default billing address.</label>
		</div>

		<input class="submit" type="submit" name="submit" value="Save">
	</form>


	<form action="/cart" method="GET" enctype="multipart/form-data">
		<h3>Delivery Address</h3>

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

		<label for="city">City / Town<span> *</span></label>
		<input type="text" name="city" required>

		<label for="region">County</label>
		<input type="text" name="region">

		<label for="country">Country<span> *</span></label>
		<input type="text" name="country" required>

		<label for="postcode">Postcode<span> *</span></label>
		<input type="text" name="postcode" required>

		<label for="phone">Phone<span> *</span></label>
		<input type="tel" name="phone" required
			pattern="(\s*\(?0\d{4}\)?(\s*|-)\d{3}(\s*|-)\d{3}\s*)|(\s*\(?0\d{3}\)?(\s*|-)\d{3}(\s*|-)\d{4}\s*)|(\s*(7|8)(\d{7}|\d{3}(\-|\s{1})\d{4})\s*)">

		<div class="checkbox-container">
			<input type="checkbox" name="defaultdelivery">
			<label for="defaultdelivery">Make this your default delivery address.</label>
		</div>

		<input class="submit" type="submit" name="submit" value="Save">
	</form>
</template>

<script>
export default {
	props: [

	],
}
</script>