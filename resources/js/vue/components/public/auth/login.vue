<template lang="html">
  <div class="auth-form">

    <form action="/customerLogin" method="GET" class="web-box dk" enctype="multipart/form-data">
      <input type="hidden" name="_token" :value="csrf">

      <label for="email">Email<span> *</span></label>
      <input type="email" name="email" required>

      <label for="password">Password<span> *</span></label>
      <label for="password" class="show-password">
        <i id="show-password" v-show="!showLoginPassword" @click="(this.showLoginPassword = !this.showLoginPassword)" class="fa-solid fa-eye"></i>
        <i id="hide-password" v-show="showLoginPassword" @click="(this.showLoginPassword = !this.showLoginPassword)" class="fa-regular fa-eye-slash"></i>
      </label>
      <input class="password" :type="!showLoginPassword ? 'password' : 'text'" name="password" required>

      <input class="submit" type="submit" name="submit" value="Login">
    </form>

  </div>
</template>


<script>
  export default {
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        showLoginPassword: false,
      }
    },
  };
</script>
