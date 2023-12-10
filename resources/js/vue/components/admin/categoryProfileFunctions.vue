<template>

  <!-- Buttons -->
  <button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <a :href="'/category-profileShowCategory/' + this.category.id + '/0'" v-if="this.category.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/category-profileShowCategory/' + this.category.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'images' }" @click="show == 'images' ? show = false : show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{ this.imagestable.count }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'products' }" @click="show == 'products' ? show = false : show = 'products'">Products<span v-show="this.productstable.count > 0"> ({{ this.productstable.count }})</span></button>

  <!-- Edit -->
	<div v-html="this.editform.html" v-show="show == 'edit'"></div>

  <!-- Images -->
	<div v-html="this.imagesform.html" v-show="show == 'images'"></div>
	<div v-html="this.imagestable.html" v-show="show == 'images'"></div>

  <!-- Products Form -->
  <div v-html="this.addproductform.html" v-show="show == 'products'"></div>
	<div v-html="this.createproductform.html" v-show="show == 'products'"></div>

	<!-- Products Table -->
	<div v-html="this.productstable.html" v-show="show == 'products'"></div>

</template>

<script>
  export default {
    props: [
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
        show: false,
				deleteLink: "showDeleteWarning('category', " + this.category.id + ", '/category-profileDelete/" + this.category.id + "')",
      };
    },
  };
</script>
