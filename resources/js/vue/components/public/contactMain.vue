<template>
  <div class="content">
    <ul class="address">
      <li><h3>Address</h3></li>
      <li v-if="this.contact['line1']">{{ this.contact['line1'] }}</li>
      <li v-if="this.contact['line2']">{{ this.contact['line2'] }}</li>
      <li v-if="this.contact['line3']">{{ this.contact['line3'] }}</li>
      <li v-if="this.contact['city']">{{ this.contact['city'] }}</li>
      <li v-if="this.contact['region']">{{ this.contact['region'] }}</li>
      <li v-if="this.contact['country']">{{ this.contact['country'] }}</li>
      <li v-if="this.contact['postcode']">{{ this.contact['postcode'] }}</li>
    </ul>

    <ul v-if="((this.contact['email'].length > 0) || (this.contact['phone'].length > 0))" class="contact-details">
      <li><h3>Contact</h3></li>
      <li v-for="(email, i) in this.contact['email']">{{ email.value }}</li>
      <li v-for="(phone, i) in this.contact['phone']">{{ phone.value }}</li>
    </ul>
  </div>

  <GMapMap class="map" :center="center" :zoom="8" map-type-id="terrain">
    <GMapMarker :position="center" />
  </GMapMap>
</template>


<script>
  export default {
    props: [
      'contact',
    ],

    data() {
      return {
        center: {lat: this.contact['lat'], lng: this.contact['lng']},
      }
    },
  };
</script>
