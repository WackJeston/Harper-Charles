<template>

  <ul class="alert-box error lt">
    <i class="fa-solid fa-xmark"></i>
    <h3> errors</h3>
      <li v-for="(error, i) in this.errormessages">{{ error }}</li>
  </ul>

</template>


<script>
  export default {
    props: [
      'errormessages',
    ],
  };
</script>
