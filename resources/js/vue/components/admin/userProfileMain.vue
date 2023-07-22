<template>

  <div class="web-box profile-main">
    <div class="wb-row">
      <ul>
        <li><strong>Name: </strong>{{ this.user.firstName }} {{ this.user.lastName }}</li>
        <li><strong>Email: </strong>{{ this.user.email }}</li>
      </ul>
    </div>
  </div>

</template>


<script>
  export default {
    props: [
      'user',
    ],
  };
</script>
