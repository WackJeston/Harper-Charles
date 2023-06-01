<template>
  <!-- Buttons -->
	<div class="page-button-row">
		<button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  	@click="show == 'edit' ? show = false : show = 'edit'">Edit Account Details</button>

		<a :href="'https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + this.invoice.fileName" target="_blank" class="page-button padding">
			Invoice
			<i class="fa-solid fa-file-invoice"></i>
		</a>
	</div>

  <div class="web-box dk section-width">
		<ul>
			<li><strong>Name:</strong> {{ this.user.firstName }} {{ this.user.lastName }}</li>
			<li><strong>Email:</strong> {{ this.user.email }}</li>
		</ul>
	</div>

	
</template>


<script>
  export default {
    props: [
      'user',
			'order',
			'invoice',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
      };
    },
  };
</script>