<template>
  <GMapMap class="map" :center="center" :zoom="10" map-type-id="terrain">
    <GMapMarker :position="center" />
  </GMapMap>
</template>


<script>
  export default {
    props: [
      'coordinates',
    ],

    data() {
      return {
        center: {lat: this.coordinates['lat'], lng: this.coordinates['lng']},
      }
    },
  };
</script>
