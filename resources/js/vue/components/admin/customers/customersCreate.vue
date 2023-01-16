<template>

  <button class="page-button" type="button" :class="{ 'button-active' : showCreate == true }"
  @click="showCreate = !showCreate">Add Customer</button>

  <form class="web-box" v-show="showCreate" action="/customersCreate" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="showCreate = false"></i>
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

    <button class="submit" type="submit">Create</button>
  </form>

</template>


<script>
  export default {
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        showCreate: false,
        showPassword: false,
      };
    },
  };
</script>
