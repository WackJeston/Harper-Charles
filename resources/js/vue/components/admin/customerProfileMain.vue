<template>

  <div class="web-box profile-main">
    <div class="wb-row">
      <ul>
        <li><strong>Name: </strong>{{ this.customer.firstName }} {{ this.customer.lastName }}</li>
        <li><strong>Email: </strong>{{ this.customer.email }}</li>
      </ul>
    </div>
  </div>

</template>


<script>
  export default {
    props: [
      'customer',
    ],
  };
</script>
