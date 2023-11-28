<template>

  <div class="home-category-grid">
		<div v-for="(category, i) in this.categories" class="grid-item-container">
			<a class="grid-item" :href="'category/' + category.id">
				<div class="background" :style="{ backgroundImage: 'url(' + this.asset + category.fileName + ')' }">
					<div class="overlay"></div>
					<h3>{{ category.title }}</h3>
				</div>
			</a>
		</div>
  </div>

</template>

<script>
export default {
  props: [
		'asset',
    'categories',
  ],
}
</script>
