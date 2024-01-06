<template>
	<!-- Buttons -->
	<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
	<a :href="'/product-profileToggleProduct/' + this.product.id + '/0'" v-if="this.product.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/product-profileToggleProduct/' + this.product.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>

	
	<button class="page-button" type="button" onclick="setShowMarker('edit')" :class="{ 'button-active': this.show == 'edit' }" @click="this.show == 'edit' ? this.show = false : this.show = 'edit'">Edit</button>
	<button class="page-button" type="button" onclick="setShowMarker('images')" :class="{ 'button-active': this.show == 'images' }" @click="this.show == 'images' ? this.show = false : this.show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{this.imagestable.count }})</span></button>
	<button class="page-button" type="button" onclick="setShowMarker('specs')" :class="{ 'button-active': this.show == 'specs' }" @click="this.show == 'specs' ? this.show = false : this.show = 'specs'">Specifications<span v-show="this.specstable.count > 0"> ({{this.specstable.count }})</span></button>
	<button class="page-button" type="button" onclick="setShowMarker('categories')" :class="{ 'button-active': this.show == 'categories' }" @click="this.show == 'categories' ? this.show = false : this.show = 'categories'">Categories<span v-show="this.categoriestable.count > 0"> ({{ this.categoriestable.count }})</span></button>
	<button class="page-button" type="button" onclick="setShowMarker('variants')" :class="{ 'button-active': this.show == 'variants' }" @click="this.show == 'variants' ? this.show = false : this.show = 'variants'">Variants<span v-show="this.variantstable.count > 0"> ({{this.variantstable.count }})</span></button>

	<!-- Edit -->
	<div v-html="this.editform.html" v-show="this.show == 'edit'"></div>

	<!-- Images -->
	<div v-html="this.imagesform.html" v-show="this.show == 'images'"></div>
	<div v-html="this.imagestable.html" v-show="this.show == 'images'"></div>

	<!-- Specs -->
	<div v-html="this.specsform.html" v-show="this.show == 'specs'"></div>
	<div v-html="this.specstable.html" v-show="this.show == 'specs'"></div>

	<!-- Categories -->
	<div v-html="this.categoryform.html" v-show="this.show == 'categories'"></div>
	<div v-html="this.categoriestable.html" v-show="this.show == 'categories'"></div>

	<!-- Variants -->
	<div v-html="this.variantsform.html" v-show="this.show == 'variants'"></div>
	<div v-html="this.variantstable.html" v-show="this.show == 'variants'"></div>
</template>


<script>
	export default {
		props: [
			'pageshowmarker',
			'product',
			'editform',
			'imagesform',
			'imagestable',
			'specsform',
			'specstable',
			'categoryform',
			'categoriestable',
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
