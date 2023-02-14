<template>

  <div class="home-category-grid">
    <a v-for="(category, i) in this.categories" class="grid-item" :href="'products/' + category.id">
      <div class="background" :style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + category.fileName + ')' }">
        <div class="overlay"></div>
        <h3>{{ category.title }}</h3>
      </div>
    </a>
  </div>

</template>

<script>
export default {
  props: [
    'categories',
  ],
}
</script>
