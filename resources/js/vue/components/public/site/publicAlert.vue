<template>

  <ul class="alert-box lt" :class="this.type" v-show="showAlert">
    <i class="fa-solid fa-xmark" @click="showAlert = false"></i>
    <li>{{ message }}</li>
  </ul>

</template>


<script>
  export default {
    props: [
      'message',
			'type',
    ],

    data() {
      return {
        showAlert: true,
      };

      
    },
  };
</script>
