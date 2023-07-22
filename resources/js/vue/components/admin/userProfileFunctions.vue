<template>
  <!-- Buttons -->
  <button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" :action="'/user-profileUpdate/' + this.user.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="firstname">First Name</label>
    <input type="text" name="firstname" maxlength="100" :value="this.user.firstName" >

    <label for="lastname">Last Name</label>
    <input type="text" name="lastname" maxlength="100" :value="this.user.lastName" >

    <label for="email">Email</label>
    <input type="text" name="email" maxlength="100" :value="this.user.email" >

    <label for="password">Password</label>
    <label for="password" class="show-password">
      <i id="show-password" v-show="!showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-solid fa-eye"></i>
      <i id="hide-password" v-show="showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-regular fa-eye-slash"></i>
    </label>
    <input class="password" :type="!showPassword ? 'password' : 'text'" name="password" value="" >

    <button class="submit" type="submit">Update</button>
  </form>
</template>


<script>
  export default {
    props: [
      'user'
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
        showPassword: false,
      };
    },

    methods: {
      delete(toggle = 1) {
        if (toggle == 1) {
          document.querySelector('.warning-overlay').style.display = 'flex';
        } else {
          document.querySelector('.warning-overlay').style.display = 'none';
        }
      },
    },
  };
</script>
