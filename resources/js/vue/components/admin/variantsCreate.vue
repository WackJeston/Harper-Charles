<template>
  <button class="page-button" type="button" onclick="setShowMarker('add')" :class="{ 'button-active' : this.show == 'add' }" @click="this.show == 'add' ? this.show = false : this.show = 'add'">Add Variant</button>

  <div v-html="this.createform.html" v-show="this.show == 'add'"></div>
</template>

<script>
export default {
	props: [
		'pageshowmarker',
		'createform',
	],

	data() {
		return {
			show: this.pageshowmarker,
		};
	},
}
</script>
