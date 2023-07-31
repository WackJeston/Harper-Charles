<template>
  <!-- Buttons -->
  <button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>

  <!-- Edit -->
  <div v-html="this.editform.html" v-show="show == 'edit'"></div>
</template>


<script>
  export default {
    props: [
      'user',
			'editform'
    ],

    data() {
      return {
        show: false,
				deleteLink: "showDeleteWarning('user', " + this.user.id + ", '/user-profileDelete/" + this.user.id + "')",
      };
    },
  };
</script>
