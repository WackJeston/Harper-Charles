
<template>
  <section class="banner" id="banner-homepage-top">

		<div v-if="this.banners.length == 1" v-for="banner in this.banners" class="banner-slide single-slide">
			<img :src="this.asset + banner.fileName" :alt="banner.fileName">
			<div class="banner-overlay"></div>
			<div class="slide-content">
				<h3 class="banner-title">{{ banner.title }}</h3>
				<h4 class="banner-title">{{ banner.description }}</h4>
			</div>
		</div>

    <carousel v-else v-bind="this.settings">
      <slide v-for="(banner, i) in banners" class="banner-slide">
				<img v-if="i == 1" :src="this.asset + banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
				<img v-else defer :src="this.asset + banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
        <div class="banner-overlay"></div>
				<div class="slide-content">
					<h3 class="banner-title">{{ banner.title }}</h3>
					<h4 class="banner-title">{{ banner.description }}</h4>
				</div>
      </slide>

      <template v-if="this.banners.length > 1" #addons>
        <navigation class="banner-nav"/>
        <!-- <pagination class="banner-pagination"/> -->
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
      // Pagination,
      Navigation,
    },

		props: [
			'asset',
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
