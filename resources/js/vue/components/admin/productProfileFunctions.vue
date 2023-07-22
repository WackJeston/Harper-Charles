<template>
	<!-- Buttons -->
	<button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'images' }" @click="show == 'images' ? show = false : show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{this.imagestable.count }})</span></button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'categories' }" @click="show == 'categories' ? show = false : show = 'categories'">Categories<span v-show="this.categoriestable.count > 0"> ({{ this.categoriestable.count }})</span></button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'variants' }" @click="show == 'variants' ? show = false : show = 'variants'">Variants<span v-show="this.variantstable.count > 0"> ({{this.variantstable.count }})</span></button>

	<!-- Edit -->
	<form class="web-box dk" v-show="show == 'edit'" :action="'/product-profileUpdate/' + this.product.id" method="POST"
		enctype="multipart/form-data">
		<i class="fa-solid fa-xmark" @click="show = false"></i>
		<input type="hidden" name="_token" :value="csrf">

		<label for="title">Title<span> *</span></label>
		<input type="text" name="title" maxlength="100" :value="this.product.title" required>

		<label for="subtitle">Subtitle</label>
		<input type="text" name="subtitle" maxlength="100" :value="this.product.subtitle">

		<label for="description">Description</label>
		<textarea type="text" name="description" maxlength="1000" :value="this.product.description"></textarea>

		<label for="productnumber">Product Number<span> *</span></label>
		<input type="text" name="productnumber" maxlength="100" :value="this.product.productNumber" required>

		<label for="price">Price (£)<span> *</span></label>
		<input type="number" min="0" step="any" name="price" maxlength="100" :value="this.product.price" required>

		<button class="submit" type="submit">Update</button>
	</form>


	<!-- Images Form -->
	<form class="web-box dk" v-show="show == 'images'" :action="'/product-profileStoreImage/' + this.product.id"
		method="POST" enctype="multipart/form-data">
		<i class="fa-solid fa-xmark" @click="show = false"></i>
		<input type="hidden" name="_token" :value="csrf">

		<label for="name">Name<span> *</span></label>
		<input type="text" name="name" maxlength="50" required>

		<label for="image">Image<span> *</span></label>
		<label class="file-input-label" for="image">
			<input @change="this.fileSelected" class="file-input" type="file" name="image" id="image" accept="image/jpg"
				required>
			<div v-if="!this.files || !this.files.length">No file selected</div>
			<div v-else v-for="file in this.files" :key="file.name">{{ file.name }}</div>
		</label>

		<button class="submit" type="submit">Upload</button>
	</form>

	<!-- Images table -->
	<div v-html="this.imagestable.html" v-show="show == 'images'"></div>


	<!-- Categories Form -->
	<form class="web-box" v-show="show == 'categories'" :action="'/product-profileAddCategory/' + this.product.id"
		method="POST" enctype="multipart/form-data">
		<i class="fa-solid fa-xmark" @click="show = false"></i>
		<input type="hidden" name="_token" :value="csrf">

		<label for="category">Select Category</label>
		<select name="category">
			<option></option>
			<option v-for="(category, i) in this.allcategories" :value="category.id">(#{{ category.id }}) {{ category.title }}
			</option>
		</select>

		<button class="submit" type="submit">Add</button>
	</form>

	<!-- Categories Table -->
	<div v-html="this.categoriestable.html" v-show="show == 'categories'"></div>


	<!-- Variants Form -->
	<form class="web-box" v-show="show == 'variants'" :action="'/product-profileAddVariant/' + this.product.id"
		method="POST" enctype="multipart/form-data">
		<i class="fa-solid fa-xmark" @click="show = false"></i>
		<input type="hidden" name="_token" :value="csrf">

		<label for="variant">Select Option</label>
		<select name="variant">
			<option></option>

			<optgroup v-for="(variant, i) in this.allvariants" :label="variant[1]">
				<option v-for="(option, i) in variant[2]" :value="option[0]">{{ option[1] }}</option>
			</optgroup>
		</select>

		<button class="submit" type="submit">Add</button>
	</form>

	<!-- Variants Table -->
	<div v-html="this.variantstable.html" v-show="show == 'variants'"></div>
</template>


<script>
export default {
	props: [
		'product',
		'imagestable',
		'categoriestable',
		'allcategories',
		'variantstable',
		'allvariants',
	],

	data() {
		return {
			csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			show: false,
			imageView: false,
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
