<template>
	<!-- Buttons -->
	<div class="vue-button-row">
		<div>
			<button class="page-button" type="button" onclick="setShowMarker('categories')" :class="{ 'button-active': this.show == 'categories' }" @click="this.show == 'categories' ? this.show = false : this.show = 'categories'">Categories<span v-show="this.categoriestable.count > 0"> ({{ this.categoriestable.count }})</span></button>
			<button class="page-button" type="button" onclick="setShowMarker('images')" :class="{ 'button-active': this.show == 'images' }" @click="this.show == 'images' ? this.show = false : this.show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{this.imagestable.count }})</span></button>
			<button class="page-button" type="button" onclick="setShowMarker('specs')" :class="{ 'button-active': this.show == 'specs' }" @click="this.show == 'specs' ? this.show = false : this.show = 'specs'">Specifications<span v-show="this.specstable.count > 0"> ({{this.specstable.count }})</span></button>
			<button class="page-button" type="button" onclick="setShowMarker('stock')" :class="{ 'button-active': this.show == 'stock' }" @click="this.show == 'stock' ? this.show = false : this.show = 'stock'">Stock<span v-show="this.product.stock > 0"> ({{this.product.stock }})</span></button>
			<button class="page-button" type="button" onclick="setShowMarker('variants')" :class="{ 'button-active': this.show == 'variants' }" @click="this.show == 'variants' ? this.show = false : this.show = 'variants'">Variants<span v-show="this.variantstable.count > 0"> ({{this.variantstable.count }})</span></button>
		</div>
		<div>
			<a :href="'/product-profileClearCache/' + this.product.id"><button class="page-button" type="button"><i class="fa-solid fa-database"></i>Cache</button></a>
			<button class="page-button" type="button" onclick="setShowMarker('edit')" :class="{ 'button-active': this.show == 'edit' }" @click="this.show == 'edit' ? this.show = false : this.show = 'edit'"><i class="fa-solid fa-pen-to-square"></i>Edit</button>
			<a :href="'/product-profileToggleProduct/' + this.product.id + '/0'" v-if="this.product.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
			<a :href="'/product-profileToggleProduct/' + this.product.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
			<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
		</div>
	</div>

	<!-- Edit -->
	<div v-html="this.editform.html" v-show="this.show == 'edit'" :class="{ 'functions-padding' : this.show == 'edit' }"></div>

	<!-- Categories -->
	<div v-html="this.categoryform.html" v-show="this.show == 'categories'"></div>
	<div v-html="this.categoriestable.html" v-show="this.show == 'categories'" :class="{ 'functions-padding' : this.show == 'categories' }"></div>

	<!-- Images -->
	<div v-html="this.imagesform.html" v-show="this.show == 'images'"></div>
	<div v-html="this.imagestable.html" v-show="this.show == 'images'" :class="{ 'functions-padding' : this.show == 'images' }"></div>

	<!-- Specs -->
	<div v-html="this.specsform.html" v-show="this.show == 'specs'"></div>
	<div v-html="this.specstable.html" v-show="this.show == 'specs'" :class="{ 'functions-padding' : this.show == 'specs' }"></div>

	<!-- Stock -->
	<div v-html="this.stockform.html" v-show="this.show == 'stock'"></div>
	<div v-html="this.stocktable.html" v-show="this.show == 'stock'" :class="{ 'functions-padding' : this.show == 'stock' }"></div>

	<!-- Variants -->
	<div v-html="this.variantsform.html" v-show="this.show == 'variants'"></div>
	<div v-html="this.variantstable.html" v-show="this.show == 'variants'" :class="{ 'functions-padding' : this.show == 'variants' }"></div>
</template>


<script>
	export default {
		props: [
			'pageshowmarker',
			'product',
			'editform',
			'categoryform',
			'categoriestable',
			'imagesform',
			'imagestable',
			'specsform',
			'specstable',
			'stockform',
			'stocktable',
			'variantsform',
			'variantstable',
		],

		data() {
			return {
				show: this.pageshowmarker,
				deleteLink: "showDeleteWarning('product', " + this.product.id + ", '/product-profileDelete/" + this.product.id + "')"
			};
		},
	};
</script>
