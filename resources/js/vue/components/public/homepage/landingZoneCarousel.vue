<template>
  <section class="landing-zone-carousel lt" id="homepage-carousel">

    <carousel :items-to-show="1" :wrapAround="true" :autoplay="7000">

      <slide v-for="lz in landingzonecarousel" :key="slide" class="lz-slide"
      :style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + lz.fileName + ')' }">
        <h2>{{ lz.title }}</h2>
        <p>{{ lz.subtitle }}</p>
        <div class="lz-overlay"></div>
      </slide>

      <template v-if="this.landingzonecarousel.length > 1" #addons>
        <navigation class="lz-nav"/>
        <pagination class="lz-pagination"/>
      </template>
    </carousel>

  </section>
</template>


<script>
  import 'vue3-carousel/dist/carousel.css';
  import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

  export default {
    props: [
      'landingzonecarousel',
    ],

    components: {
      Carousel,
      Slide,
      Pagination,
      Navigation,
    },
  };
</script>
