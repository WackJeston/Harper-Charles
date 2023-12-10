<template>

  <!-- Buttons -->
  <button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <a :href="'/variant-profileShowVariant/' + this.variant.id + '/0'" v-if="this.variant.active == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/variant-profileShowVariant/' + this.variant.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'createOption' }" @click="show == 'createOption' ? show = false : show = 'createOption'">Create Option</button>

  <!-- Edit -->
	<div v-html="this.editform.html" v-show="show == 'edit'"></div>

  <!-- Options -->
	<div v-html="this.subvariantsform.html" v-show="show == 'createOption'"></div>
  <div v-html="this.subvariantstable.html"></div>
</template>


<script>
  export default {
    props: [
      'variant',
			'editform',
			'subvariantsform',
      'subvariantstable',
    ],

    data() {
      return {
        show: null,
				deleteLink: "showDeleteWarning('variant', " + this.variant.id + ", '/variant-profileDelete/" + this.variant.id + "')",
      };
    },
  };
</script>
