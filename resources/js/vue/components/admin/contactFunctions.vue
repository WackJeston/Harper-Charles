<template>
  <!-- Buttons -->
  <button class="page-button" type="button" onclick="setShowMarker('edit')" :class="{ 'button-active' : this.show == 'edit' }" @click="this.show == 'edit' ? this.show = false : this.show = 'edit'">Edit Address</button>
  <button class="page-button" type="button" onclick="setShowMarker('email')" :class="{ 'button-active' : this.show == 'email' }" @click="this.show == 'email' ? this.show = false : this.show = 'email'">Emails<span v-show="this.emailstable.count > 0"> ({{ this.emailstable.count }})</span></button>
  <button class="page-button" type="button" onclick="setShowMarker('phone')" :class="{ 'button-active' : this.show == 'phone' }" @click="this.show == 'phone' ? this.show = false : this.show = 'phone'">Phones<span v-show="this.phonestable.count > 0"> ({{ this.phonestable.count }})</span></button>
  <button class="page-button" type="button" onclick="setShowMarker('url')" :class="{ 'button-active' : this.show == 'url' }" @click="this.show == 'url' ? this.show = false : this.show = 'url'">URLs<span v-show="this.urlstable.count > 0"> ({{ this.urlstable.count }})</span></button>

  <!-- Edit -->
  <div v-html="this.editform.html" v-show="this.show == 'edit'"></div>
	<!-- <form :action="'/contactUpdateAddress/' + this.lat + '/' + this.lng"></form> -->

  <!-- Emails -->
  <div v-html="this.emailform.html" v-show="this.show == 'email'"></div>
	<div v-html="this.emailstable.html" v-show="this.show == 'email'"></div>

  <!-- Phones -->
  <div v-html="this.phoneform.html" v-show="this.show == 'phone'"></div>
	<div v-html="this.phonestable.html" v-show="this.show == 'phone'"></div>

<!-- URLs -->
<div v-html="this.urlform.html" v-show="this.show == 'url'"></div>
<div v-html="this.urlstable.html" v-show="this.show == 'url'"></div>
</template>


<script>
  export default {
    props: [
			'pageshowmarker',
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
        show: this.pageshowmarker,
      };
    },
  };
</script>
