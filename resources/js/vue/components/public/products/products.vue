<template>

  <section v-if="this.selectedImages.length" class="landing-zone-carousel lt" id="products-carousel">
    <div class="carousel-content">
      <h2 v-show="this.categorySubtitle">{{ this.categorySubtitle }}</h2>
      <p v-show="this.categoryDesc">{{ this.categoryDesc }}</p>
    </div>

    <carousel v-if="this.selectedImages.length > 1" :items-to-show="1" :wrapAround="true" :autoplay="7000">
      <slide  v-for="(image, i) in this.selectedImages" :key="slide" class="lz-slide"
      :style="{ backgroundImage: 'url(https://template-website-bucket.s3.eu-west-2.amazonaws.com/assets/' + image.fileName + ')' }">
        <div v-show="this.categorySubtitle || this.categoryDesc" class="lz-overlay"></div>
      </slide>
    </carousel>

    <carousel v-else-if="this.selectedImages.length == 1" :items-to-show="1">
      <slide class="lz-slide" :style="{ backgroundImage: 'url(https://template-website-bucket.s3.eu-west-2.amazonaws.com/assets/' + this.selectedImages[0].fileName + ')' }">
        <div v-show="this.categorySubtitle || this.categoryDesc" class="lz-overlay"></div>
      </slide>
    </carousel>
  </section>

  <div class="category-row lt">
    <button v-for="(category, i) in this.categories" :class="{ 'active' : this.selectedCategory == category.id }" class=""
    @click="(this.selectedCategory == category.id ? this.selectedCategory = false : this.selectedCategory = category.id), (this.selectProducts())" type="button" name="button">{{ category.title }}</button>
  </div>

  <div v-if="(this.categorySubtitle || this.categoryDesc) && !this.selectedImages.length" class="category-info lt">
    <h2 v-show="this.categorySubtitle">{{ this.categorySubtitle }}</h2>
    <p v-show="this.categoryDesc">{{ this.categoryDesc }}</p>
  </div>

  <section class="card-display-zone">

    <div v-for="(product, i) in this.selectedProducts" class="display-card">
      <a :href="'/product-page/' + product.id">
        <div v-if="product.fileName" class="card-image"
        :style="{ backgroundImage: 'url(https://template-website-bucket.s3.eu-west-2.amazonaws.com/assets/' + product.fileName + ')' }"></div>
        <div v-else class="card-image">
          <i class="fa-solid fa-couch"></i>
        </div>
      </a>
      <div class="card-content dk">
        <a :href="'/product-page/' + product.id"><h3>{{ product.title }}</h3></a>
        <p>{{ product.subtitle }}</p>
      </div>
      <div class="button-row">
        <a :href="'/product-page/' + product.id"><button class="lt" type="button">view</button></a>
        <!-- <a href="#"><button class="lt" type="button">Add to Cart</button></a> -->
      </div>
    </div>

  </section>
</template>


<script>
  import 'vue3-carousel/dist/carousel.css';
  import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

  export default {
    props: [
      'products',
      'categories',
      'categoryimages',
      'initialcategory',
    ],

    components: {
      Carousel,
      Slide,
      Pagination,
      Navigation,
    },

    data() {
      return {
        selectedCategory: this.initialcategory,
        categorySubtitle: null,
        categoryDesc: null,
        selectedProducts: [],
        selectedImages: [],
      }
    },

    methods: {
      selectProducts() {
        this.selectedProducts = [];
        this.selectedImages = [];
        this.categorySubtitle = null;
        this.categoryDesc = null;
        let productCounter = -1;
        let imageCounter = -1;
        if (this.selectedCategory == 0) {
          for (var i = 0; i < this.products.length; i++) {
            this.selectedProducts.push([]);
            productCounter++;
            this.selectedProducts[productCounter]['id'] = this.products[i].id;
            this.selectedProducts[productCounter]['title'] = this.products[i].title;
            this.selectedProducts[productCounter]['subtitle'] = this.products[i].subtitle;
            this.selectedProducts[productCounter]['fileName'] = this.products[i].fileName;
          }
          for (var i = 0; i < this.categoryimages.length; i++) {
            this.selectedImages.push([]);
            imageCounter++;
            this.selectedImages[imageCounter]['fileName'] = this.categoryimages[i].fileName;
          }
        } else {
          for (var i = 0; i < this.products.length; i++) {
            if (this.products[i].categoryId == this.selectedCategory) {
              this.selectedProducts.push([]);
              productCounter++;
              this.selectedProducts[productCounter]['id'] = this.products[i].id;
              this.selectedProducts[productCounter]['title'] = this.products[i].title;
              this.selectedProducts[productCounter]['subtitle'] = this.products[i].subtitle;
              this.selectedProducts[productCounter]['fileName'] = this.products[i].fileName;
            }
          }
          for (var i = 0; i < this.categoryimages.length; i++) {
            if (this.categoryimages[i].categoryId == this.selectedCategory) {
              this.selectedImages.push([]);
              imageCounter++;
              this.selectedImages[imageCounter]['fileName'] = this.categoryimages[i].fileName;
            }
          }
          for (var i = 0; i < this.categories.length; i++) {
            if (this.categories[i].id == this.selectedCategory) {
              this.categorySubtitle = this.categories[i].subtitle;
              this.categoryDesc = this.categories[i].description;
            }
          }
        }
      }
    },

    created() {
      this.selectProducts();
    },
  };
</script>
