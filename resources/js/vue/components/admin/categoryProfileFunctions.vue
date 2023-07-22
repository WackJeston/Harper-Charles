<template>

  <!-- Buttons -->
  <button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <a :href="'/category-profileShowCategory/' + this.category.id + '/0'" v-if="this.category.show == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/category-profileShowCategory/' + this.category.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'images' }"
  @click="show == 'images' ? show = false : show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{ this.imagestable.count }})</span></button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'products' }"
  @click="show == 'products' ? show = false : show = 'products'">Products<span v-show="this.productstable.count > 0"> ({{ this.productstable.count }})</span></button>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" :action="'/category-profileUpdate/' + this.category.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" :value="this.category.title" required>

    <label for="subtitle">Subtitle</label>
    <input type="text" name="subtitle" maxlength="100" :value="this.category.subtitle">

    <label for="description">Description</label>
    <textarea type="text" name="description" maxlength="1000" :value="this.category.description"></textarea>

    <button class="submit" type="submit">Update</button>
  </form>

  <!-- Images Form -->
  <form class="web-box dk" v-show="show == 'images'" :action="'/category-profileStoreImage/' + this.category.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="name">Name<span> *</span></label>
    <input type="text" name="name" maxlength="100" required>

    <label for="image">Image<span> *</span></label>
    <label class="file-input-label" for="image">
      <input @change="this.fileSelected" class="file-input" type="file" name="image" id="image" accept="image/jpg" required>
      <div v-if="!this.files || !this.files.length">No file selected</div>
      <div v-else v-for="file in this.files" :key="file.name">{{file.name}}</div>
    </label>

    <button class="submit" type="submit">Upload</button>
  </form>

	<!-- Images Table -->
	<div v-html="this.imagestable.html" v-show="show == 'images'"></div>


  <!-- Products Form -->
  <h3 class="form-title" v-show="show == 'products'">Add Existing Product</h3>

  <form class="web-box" v-show="show == 'products'" :action="'/category-profileAddProduct/' + this.category.id" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_token" :value="csrf">

    <label for="product">Select Product</label>
    <select name="product">
      <option></option>
      <option v-for="(product, i) in this.allproducts" :value="product.id">(#{{ product.id }}) {{ product.title }}</option>
    </select>

    <button class="submit" type="submit">Add</button>
  </form>

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
      'imagestable',
			'productstable',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
        files: null,
      };
    },

    methods: {
      fileSelected(e) {
        this.files = e.target.files;
      },
    },
  };
</script>
