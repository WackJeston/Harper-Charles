<template>
	<!-- Buttons -->
	<div class="vue-button-row">
		<div>
			<button class="page-button" type="button" onclick="setShowMarker('notes')" :class="{ 'button-active': this.show == 'notes' }" @click="this.show == 'notes' ? this.show = false : this.show = 'notes'">Notes<span v-show="this.notestable.count > 0"> ({{this.notestable.count }})</span></button>
		</div>
		<div>
			<a :href="this.order.invoice" target="_blank" class="page-button padding">Invoice</a>
		</div>
	</div>

	<!-- Notes -->
	<div v-html="this.notesform.html" v-show="this.show == 'notes'"></div>
	<div v-html="this.notestable.html" v-show="this.show == 'notes'"></div>
</template>


<script>
	export default {
		props: [
			'pageshowmarker',
			'order',
			'notesform',
			'notestable',
		],

		data() {
			return {
				show: this.pageshowmarker,
			};
		},
	};
</script>
