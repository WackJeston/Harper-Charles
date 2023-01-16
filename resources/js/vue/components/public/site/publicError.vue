<template>

  <ul class="alert-box error lt" v-show="showAlert">
    <i class="fa-solid fa-xmark" @click="showAlert = false"></i>
    <li v-for="(error, i) in this.errormessages">{{ error }}</li>
  </ul>

</template>


<script>
  export default {
    props: [
      'errormessages',
      'errorcount',
    ],

    data() {
      return {
        showAlert: true,
      }
    },
  };
</script>
