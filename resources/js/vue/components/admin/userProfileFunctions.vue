<template>
  <!-- Buttons -->
  <button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
	<button class="page-button" type="button" :class="{ 'button-active': show == 'orders' }" @click="show == 'orders' ? show = false : show = 'orders'">Orders<span v-show="this.orderstable.count > 0"> ({{this.orderstable.count }})</span></button>

  <!-- Edit -->
  <div v-html="this.editform.html" v-show="show == 'edit'"></div>

<!-- Edit -->
<div v-html="this.orderstable.html" v-show="show == 'orders'"></div>
</template>


<script>
  export default {
    props: [
      'user',
			'editform',
			'orderstable'
    ],

    data() {
      return {
        show: false,
				deleteLink: "showDeleteWarning('user', " + this.user.id + ", '/user-profileDelete/" + this.user.id + "')",
      };
    },
  };
</script>
