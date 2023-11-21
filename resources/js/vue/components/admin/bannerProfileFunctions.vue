<template>

  <!-- Buttons -->
  <a :href="'/banner-profileToggleBanner/' + this.banner.id + '/0'" v-if="this.banner.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/banner-profileToggleBanner/' + this.banner.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'addSlide' }" @click="show == 'addSlide' ? show = false : show = 'addSlide'">Add Slide</button>

	<div v-html="this.slideform.html" v-show="show == 'addSlide'"></div>
</template>


<script>
  export default {
    props: [
      'banner',
			'slideform',
    ],

		data() {
			return {
				show: null,
			};
		},
  };
</script>
