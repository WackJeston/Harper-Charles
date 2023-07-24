<template>
  <!-- Buttons -->
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit Address</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'email' }"
  @click="show == 'email' ? show = false : show = 'email'">Emails<span v-show="this.emailstable.count > 0"> ({{ this.emailstable.count }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'phone' }"
  @click="show == 'phone' ? show = false : show = 'phone'">Phones<span v-show="this.phonestable.count > 0"> ({{ this.phonestable.count }})</span></button>

  <!-- Edit -->
  <div v-html="this.editform.html" v-show="show == 'edit'"></div>

	<form :action="'/contactUpdateAddress/' + this.lat + '/' + this.lng">
  </form>

  <!-- Emails -->
  <div v-html="this.emailform.html" v-show="show == 'email'"></div>
	<div v-html="this.emailstable.html" v-show="show == 'email'"></div>

  <!-- Phones -->
  <div v-html="this.phoneform.html" v-show="show == 'phone'"></div>
	<div v-html="this.phonestable.html" v-show="show == 'phone'"></div>
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
    ],

    data() {
      return {
        show: false,
      };
    },

    methods: {
      async updateAddressMap() {
        try {
          let query = document.getElementById('line1').value.replace(',', '');
          query += ' ' + document.getElementById('line2').value.replace(',', '');
          query += ' ' + document.getElementById('line3').value.replace(',', '');
          query += ' ' + document.getElementById('city').value.replace(',', '');
          query += ' ' + document.getElementById('postcode').value.replace(',', '');

          query = encodeURI(query);

          this.geoData = await this.$http.post(
            'https://maps.googleapis.com/maps/api/geocode/json?address=' + query + '&key=' + process.env.MIX_GOOGLE_MAPS_KEY
          );

        } catch (err) {
          console.log(err);
        } finally {
          uploadLatLng(this.geoData.data.results[0].geometry.location.lat, this.geoData.data.results[0].geometry.location.lng);
        }
      },

			async uploadLatLng(lat, lng) {
				try {
          this.result = await this.$http.post(
            'contactUploadLatLng/' + lat + '/' + lng
          );
        } catch (err) {
          console.log(err);
        } finally {
					console.log(this.result);
          setTimeout(() => {
            document.getElementById('updateAddressMap').submit();
          }, 10);
        }
			},
    },
  };
</script>
