<template lang="html">
  <div class="auth-form">

    <form action="/customerSignup" method="GET" class="web-box dk" enctype="multipart/form-data">
      <input type="hidden" name="_token" :value="csrf">

      <label for="firstname">First Name<span> *</span></label>
      <input type="text" name="firstname" required>

      <label for="lastname">Last Name<span> *</span></label>
      <input type="text" name="lastname" required>

      <label for="email">Email<span> *</span></label>
      <input type="email" name="email" required>

      <label for="password">Password<span> *</span></label>
      <label for="password" class="show-password">
        <i id="show-password" v-show="!showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-solid fa-eye"></i>
        <i id="hide-password" v-show="showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-regular fa-eye-slash"></i>
      </label>
      <input class="password" :type="!showPassword ? 'password' : 'text'" name="password" required>

      <label for="password_confirmation">Confirm Password<span> *</span></label>
      <label for="password_confirmation" class="show-password">
        <i id="show-password" v-show="!showConfirmPassword" @click="(this.showConfirmPassword = !this.showConfirmPassword)" class="fa-solid fa-eye"></i>
        <i id="hide-password" v-show="showConfirmPassword" @click="(this.showConfirmPassword = !this.showConfirmPassword)" class="fa-regular fa-eye-slash"></i>
      </label>
      <input class="password" :type="!showConfirmPassword ? 'password' : 'text'" name="password_confirmation" required>

      <input class="submit" type="submit" name="submit" value="Sign Up">
    </form>

  </div>
</template>


<script>
  export default {
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        showPassword: false,
        showConfirmPassword: false,
      }
    },
  };
</script>
