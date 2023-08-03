<template>

  <!-- Buttons -->
  <button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <a :href="'/category-profileShowCategory/' + this.category.id + '/0'" v-if="this.category.show == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
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
  <!-- <h3 class="form-title" v-show="show == 'products'">Add Existing Product</h3> -->
  <div v-html="this.addproductform.html" v-show="show == 'products'"></div>

  <h3 class="form-title" v-show="show == 'products'">Create New Product</h3>

  <form class="web-box" v-show="show == 'products'" :action="'/category-profileCreateProduct/' + this.category.id" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" required>

    <label for="subtitle">Subtitle</label>
    <input type="text" name="subtitle" maxlength="100">

    <label for="description">Description</label>
    <textarea type="text" name="description" maxlength="1000"></textarea>

    <label for="productnumber">Product Number<span> *</span></label>
    <input type="text" name="productnumber" maxlength="100" required>

    <label for="price">Price (£)<span> *</span></label>
    <input type="number" min="0" step="any" name="price" maxlength="100" required>


    <button class="submit" type="submit">Create</button>
  </form>

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
