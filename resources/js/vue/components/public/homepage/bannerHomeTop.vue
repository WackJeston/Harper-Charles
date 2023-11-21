<template>
  <section class="banner" id="banner-homepage-top">

		<div v-if="this.banners.length == 1" v-for="banner in this.banners" class="banner-slide single-slide">
			<img :src="banner.fileName" :alt="banner.fileName">
			<div class="banner-overlay"></div>
		</div>

    <carousel v-else v-bind="this.settings">
      <slide v-for="(banner, i) in banners" class="banner-slide">
				<img v-if="i == 1" :src="banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
				<img v-else defer :src="banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
        <div class="banner-overlay"></div>
				<h3 class="banner-title">{{ banner.title }}</h3>
      </slide>

      <template v-if="this.banners.length > 1" #addons>
        <navigation class="banner-nav"/>
        <pagination class="banner-pagination"/>
      </template>
    </carousel>

  </section>
</template>


<script>
	import { defineComponent } from 'vue'
  import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

  import 'vue3-carousel/dist/carousel.css';

	export default defineComponent({
		name: 'Basic',
    components: {
      Carousel,
      Slide,
      Pagination,
      Navigation,
    },

		props: [
      'banners',
    ],

		data: () => ({
			settings: {
				itemsToShow: 1,
				wrapAround: true,
				autoplay: 6000,
			},
		}),
	});
</script>
