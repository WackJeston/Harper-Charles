<template>
	<!-- Buttons -->
	<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'images' }" @click="show == 'images' ? show = false : show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{this.imagestable.count }})</span></button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'categories' }" @click="show == 'categories' ? show = false : show = 'categories'">Categories<span v-show="this.categoriestable.count > 0"> ({{ this.categoriestable.count }})</span></button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'variants' }" @click="show == 'variants' ? show = false : show = 'variants'">Variants<span v-show="this.variantstable.count > 0"> ({{this.variantstable.count }})</span></button>

	<!-- Edit -->
	<div v-html="this.editform.html" v-show="show == 'edit'"></div>

	<!-- Images -->
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

	<div v-html="this.imagesform.html" v-show="show == 'images'"></div>
	<div v-html="this.imagestable.html" v-show="show == 'images'"></div>

	<!-- Categories -->
	<div v-html="this.categoryform.html" v-show="show == 'categories'"></div>
	<div v-html="this.categoriestable.html" v-show="show == 'categories'"></div>

	<!-- Variants -->
	<div v-html="this.variantsform.html" v-show="show == 'variants'"></div>
	<div v-html="this.variantstable.html" v-show="show == 'variants'"></div>
</template>


<script>
export default {
	props: [
		'product',
		'editform',
		'imagesform',
		'imagestable',
		'categoryform',
		'categoriestable',
		'variantsform',
		'variantstable',
	],

	data() {
		return {
			show: false,
			files: null,
			deleteLink: "showDeleteWarning('product', " + this.product.id + ", '/product-profileDelete/" + this.product.id + "')",
		};
	},

	methods: {
		fileSelected(e) {
			this.files = e.target.files;
		},
	},
};
</script>
