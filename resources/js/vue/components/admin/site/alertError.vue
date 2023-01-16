<template>

  <ul class="alert-box error lt" v-show="showAlert">
    <i class="fa-solid fa-xmark" @click="showAlert = false"></i>
    <h3 v-if="this.errorcount == 1">{{ this.errorcount }} Error</h3>
    <h3 v-else>{{ this.errorcount }} Errors</h3>
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
