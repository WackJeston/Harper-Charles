<template>
  <!-- Buttons -->
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit Address</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'email' }" @click="show == 'email' ? show = false : show = 'email'">Emails<span v-show="this.emailstable.count > 0"> ({{ this.emailstable.count }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'phone' }" @click="show == 'phone' ? show = false : show = 'phone'">Phones<span v-show="this.phonestable.count > 0"> ({{ this.phonestable.count }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'url' }" @click="show == 'url' ? show = false : show = 'url'">URLs<span v-show="this.urlstable.count > 0"> ({{ this.urlstable.count }})</span></button>

  <!-- Edit -->
  <div v-html="this.editform.html" v-show="show == 'edit'"></div>
	<!-- <form :action="'/contactUpdateAddress/' + this.lat + '/' + this.lng"></form> -->

  <!-- Emails -->
  <div v-html="this.emailform.html" v-show="show == 'email'"></div>
	<div v-html="this.emailstable.html" v-show="show == 'email'"></div>

  <!-- Phones -->
  <div v-html="this.phoneform.html" v-show="show == 'phone'"></div>
	<div v-html="this.phonestable.html" v-show="show == 'phone'"></div>

<!-- URLs -->
<div v-html="this.urlform.html" v-show="show == 'url'"></div>
<div v-html="this.urlstable.html" v-show="show == 'url'"></div>
</template>


<script>
  export default {
    props: [
      'contact',
			'editform',
			'emailform',
			'emailstable',
			'phoneform',
			'phonestable',
			'urlform',
			'urlstable',
    ],

    data() {
      return {
        show: false,
      };
    },
  };
</script>
