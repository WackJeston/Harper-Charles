<template>

  <button class="page-button" type="button" :class="{ 'button-active' : showCreate == true }" @click="showCreate = !showCreate">Add Product</button>

  <div v-html="this.createform.html" v-show="showCreate"></div>

</template>


<script>
  export default {
		props: [
			'createform'
		],

    data() {
      return {
        showCreate: false,
      };
    },
  };
</script>
