<template>
  <!-- Buttons -->
	<div class="vue-button-row">
		<div>
			<button class="page-button" type="button" onclick="setShowMarker('images')" :class="{ 'button-active' : this.show == 'images' }" @click="this.show == 'images' ? this.show = false : this.show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{ this.imagestable.count }})</span></button>
			<button class="page-button" type="button" onclick="setShowMarker('products')" :class="{ 'button-active' : this.show == 'products' }" @click="this.show == 'products' ? this.show = false : this.show = 'products'">Products<span v-show="this.productstable.count > 0"> ({{ this.productstable.count }})</span></button>
		</div>
		<div>
			<a :href="'/category-profileClearCache/' + this.category.id"><button class="page-button" type="button"><i class="fa-solid fa-database"></i>Cache</button></a>
			<button class="page-button" type="button" onclick="setShowMarker('edit')" :class="{ 'button-active' : this.show == 'edit' }" @click="this.show == 'edit' ? this.show = false : this.show = 'edit'">Edit</button>
			<a :href="'/category-profileShowCategory/' + this.category.id + '/0'" v-if="this.category.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
			<a :href="'/category-profileShowCategory/' + this.category.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
			<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
		</div>
	</div>
  

  <!-- Edit -->
	<div v-html="this.editform.html" v-show="this.show == 'edit'"></div>

  <!-- Images -->
	<div v-html="this.imagesform.html" v-show="this.show == 'images'"></div>
	<div v-html="this.imagestable.html" v-show="this.show == 'images'"></div>

  <!-- Products Form -->
  <div v-html="this.addproductform.html" v-show="this.show == 'products'"></div>
	<div v-html="this.createproductform.html" v-show="this.show == 'products'"></div>

	<!-- Products Table -->
	<div v-html="this.productstable.html" v-show="this.show == 'products'"></div>
</template>

<script>
  export default {
    props: [
			'pageshowmarker',
      'category',
			'editform',
			'imagesform',
      'imagestable',
			'addproductform',
			'createproductform',
			'productstable',
    ],

    data() {
      return {
        show: this.pageshowmarker,
				deleteLink: "showDeleteWarning('category', " + this.category.id + ", '/category-profileDelete/" + this.category.id + "')",
      };
    },
  };
</script>
