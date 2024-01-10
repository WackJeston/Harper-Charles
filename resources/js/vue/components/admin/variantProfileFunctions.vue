<template>
  <!-- Buttons -->
  <div class="vue-button-row">
		<div>

		</div>
		<div>
			<button class="page-button" type="button" onclick="setShowMarker('createOption')" :class="{ 'button-active' : this.show == 'createOption' }" @click="this.show == 'createOption' ? this.show = false : this.show = 'createOption'">Create Option</button>
			<button class="page-button" type="button" onclick="setShowMarker('edit')" :class="{ 'button-active' : this.show == 'edit' }" @click="this.show == 'edit' ? this.show = false : this.show = 'edit'">Edit</button>
			<a :href="'/variant-profileShowVariant/' + this.variant.id + '/0'" v-if="this.variant.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
			<a :href="'/variant-profileShowVariant/' + this.variant.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
			<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
		</div>
	</div>

  <!-- Edit -->
	<div v-html="this.editform.html" v-show="this.show == 'edit'"></div>

  <!-- Options -->
	<div v-html="this.subvariantsform.html" v-show="this.show == 'createOption'"></div>
  <div v-html="this.subvariantstable.html"></div>
</template>


<script>
  export default {
    props: [
			'pageshowmarker',
      'variant',
			'editform',
			'subvariantsform',
      'subvariantstable',
    ],

    data() {
      return {
        show: this.pageshowmarker,
				deleteLink: "showDeleteWarning('variant', " + this.variant.id + ", '/variant-profileDelete/" + this.variant.id + "')",
      };
    },
  };
</script>
