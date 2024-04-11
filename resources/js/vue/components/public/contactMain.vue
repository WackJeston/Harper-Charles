<template>
	<div class="form-width-control">	
		<div class="form-content">
			<h2>Enquiry Form</h2>
			<p>For all enquiries please leave details here or drop us an email and our sales team will be in touch to discuss your project. We look forward to working with you.</p>
		</div>

		<form action="/contactCreateEnquiry">
			<input type="hidden" name="_token" :value="csrf">

			<label for="name">Name<span class="red"> *</span></label>
			<input type="text" name="name" required>

			<label for="email">Email<span class="red"> *</span></label>
			<input type="email" name="email" required>

			<label for="phone">Phone</label>
			<input type="tel" name="phone">

			<label for="subject">Subject<span class="red"> *</span></label>
			<input type="text" name="subject" required>

			<label for="message">Message<span class="red"> *</span></label>
			<textarea name="message" required></textarea>

			<input type="submit" value="Send" class="submit page-button padding">
		</form>
	</div>
</template>


<script>
  export default {
    props: [
			'address',
      'contact',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      }
    },
  };
</script>
