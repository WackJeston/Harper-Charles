<template>

  <ul class="alert-box success lt" v-show="showAlert">
    <i class="fa-solid fa-xmark" @click="showAlert = false"></i>
    <li>{{ successmessage }}</li>
  </ul>

</template>


<script>
  export default {
    props: [
      'successmessage',
    ],

    data() {
      return {
        showAlert: true,
      };
    },
  };
</script>
