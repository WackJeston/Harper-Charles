<template>
  <!-- Buttons -->
	<div class="vue-button-row">
		<div>
			<button class="page-button" type="button" onclick="setShowMarker('orders')" :class="{ 'button-active': this.show == 'orders' }" @click="this.show == 'orders' ? this.show = false : this.show = 'orders'">Orders<span v-show="this.orderstable.count > 0"> ({{this.orderstable.count }})</span></button>
		</div>
		<div>

  		<button class="page-button" type="button" onclick="setShowMarker('edit')" :class="{ 'button-active' : this.show == 'edit' }" @click="this.show == 'edit' ? this.show = false : this.show = 'edit'">Edit</button>
			<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
		</div>
	</div>

  <!-- Edit -->
  <div v-html="this.editform.html" v-show="this.show == 'edit'"></div>

	<!-- Orders -->
	<div v-html="this.orderstable.html" v-show="this.show == 'orders'"></div>
</template>


<script>
  export default {
    props: [
			'pageshowmarker',
      'user',
			'editform',
			'orderstable'
    ],

    data() {
      return {
        show: this.pageshowmarker,
				deleteLink: "showDeleteWarning('user', " + this.user.id + ", '/user-profileDelete/" + this.user.id + "')",
      };
    },
  };
</script>
