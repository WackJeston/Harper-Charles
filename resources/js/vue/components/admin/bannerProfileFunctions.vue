<template>
  <!-- Buttons -->
	<div class="vue-button-row">
		<div>
			
		</div>
		<div>
			<button class="page-button" type="button" onclick="setShowMarker('addSlide')" :class="{ 'button-active' : this.show == 'addSlide' }" @click="this.show == 'addSlide' ? this.show = false : this.show = 'addSlide'">Add Slide</button>
			<a :href="'/banner-profileToggleBanner/' + this.banner.id + '/0'" v-if="this.banner.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
			<a :href="'/banner-profileToggleBanner/' + this.banner.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
		</div>
	</div>

	<div v-html="this.slideform.html" v-show="this.show == 'addSlide'" :class="{ 'functions-padding' : this.show == 'addSlide' }"></div>
</template>


<script>
  export default {
    props: [
			'pageshowmarker',
      'banner',
			'slideform',
    ],

		data() {
			return {
				show: this.pageshowmarker
			};
		},
  };
</script>
