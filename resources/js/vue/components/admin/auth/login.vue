<template>
  <div class="login-form dk">

    <!-- Login Form -->
    <form action="/adminLogin" method="GET" enctype="multipart/form-data">
      <input type="hidden" name="_token" :value="csrf">

      <label for="email">Email<span> *</span></label>
      <input type="email" name="email" required>

      <label for="password">Password<span> *</span></label>
      <label for="password" class="show-password" required>
        <i id="show-password" v-show="!showLoginPassword" @click="(this.showLoginPassword = !this.showLoginPassword)" class="fa-solid fa-eye"></i>
        <i id="hide-password" v-show="showLoginPassword" @click="(this.showLoginPassword = !this.showLoginPassword)" class="fa-regular fa-eye-slash"></i>
      </label>
      <input class="password" :type="!showLoginPassword ? 'password' : 'text'" required autocomplete="one-time-code">

      <input class="submit" type="submit" name="submit" value="Login">
    </form>

    <!-- <a href="/admin-registration">Register</a> -->

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
