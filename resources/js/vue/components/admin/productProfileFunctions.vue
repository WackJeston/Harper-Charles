<template>
	<!-- Buttons -->
	<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
	<a :href="'/product-profileToggleProduct/' + this.product.id + '/0'" v-if="this.product.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/product-profileToggleProduct/' + this.product.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'images' }" @click="show == 'images' ? show = false : show = 'images'">Images<span v-show="this.imagestable.count > 0"> ({{this.imagestable.count }})</span></button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'specs' }" @click="show == 'specs' ? show = false : show = 'specs'">Specifications<span v-show="this.specstable.count > 0"> ({{this.specstable.count }})</span></button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'categories' }" @click="show == 'categories' ? show = false : show = 'categories'">Categories<span v-show="this.categoriestable.count > 0"> ({{ this.categoriestable.count }})</span></button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'variants' }" @click="show == 'variants' ? show = false : show = 'variants'">Variants<span v-show="this.variantstable.count > 0"> ({{this.variantstable.count }})</span></button>

	<!-- Edit -->
	<div v-html="this.editform.html" v-show="show == 'edit'"></div>

	<!-- Images -->
	<div v-html="this.imagesform.html" v-show="show == 'images'"></div>
	<div v-html="this.imagestable.html" v-show="show == 'images'"></div>

	<!-- Specs -->
	<div v-html="this.specsform.html" v-show="show == 'specs'"></div>
	<div v-html="this.specstable.html" v-show="show == 'specs'"></div>

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
		'specsform',
		'specstable',
		'categoryform',
		'categoriestable',
		'variantsform',
		'variantstable',
	],

	data() {
		return {
			show: false,
			deleteLink: "showDeleteWarning('product', " + this.product.id + ", '/product-profileDelete/" + this.product.id + "')",
		};
	},
};
</script>
