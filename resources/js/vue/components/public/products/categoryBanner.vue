<template>
	<section class="banner" id="category-banner">

	<div v-if="this.title || this.description" class="banner-title-container">
		<div class="banner-title-inner-container">
			<h1 v-if="this.title">{{ this.title }}</h1>
			<h3 v-if="this.description">{{ this.description }}</h3>
		</div>
	</div>

	<div v-if="this.banners.length == 1" v-for="banner in this.banners" class="banner-slide single-slide">
		<img :src="this.publicasset + banner.fileName" :alt="banner.fileName">
		<div class="banner-overlay"></div>
		<div class="slide-content">
			<h3 class="banner-title">{{ banner.title }}</h3>
			<h4 class="banner-title">{{ banner.description }}</h4>
		</div>
	</div>

	<carousel v-else v-bind="this.settings">
		<slide v-for="(banner, i) in banners" class="banner-slide">
			<img v-if="i == 1" :src="this.publicasset + banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
			<img v-else defer :src="this.publicasset + banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
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
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default {
	components: {
		Carousel,
		Slide,
		// Pagination,
		Navigation,
	},

	props: [
		'publicasset',
    'banners',
		'title',
		'description'
	],

	data: () => ({
		settings: {
			itemsToShow: 1,
			wrapAround: true,
			autoplay: 6000,
		},
	}),
};
</script>
