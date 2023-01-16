<template>
  <!-- Buttons -->
  <button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>

  <!-- Delete Warning -->
  <div @click="this.delete(0)" class="warning-overlay">
    <div class="web-box warning-box dk">
      <h3 class="warning">WARNING</h3>
      <p>This will permanently delete <strong>Customer #{{ this.customer.id }}</strong></p>
      <div class="row">
        <a :href="'/customer-profileDelete/' + this.customer.id"><button type="button" name="delete" class="delete">Delete</button></a>
        <button @click="this.delete(0)" type="button" name="cancel" class="cancel">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" :action="'/customer-profileUpdate/' + this.customer.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="firstname">First Name<span> *</span></label>
    <input type="text" name="firstname" maxlength="100" :value="this.customer.firstName" required>

    <label for="lastname">Last Name<span> *</span></label>
    <input type="text" name="lastname" maxlength="100" :value="this.customer.lastName" required>

    <label for="email">Email<span> *</span></label>
    <input type="text" name="email" maxlength="100" :value="this.customer.email" required>

    <label for="password">Password<span> *</span></label>
    <label for="password" class="show-password">
      <i id="show-password" v-show="!showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-solid fa-eye"></i>
      <i id="hide-password" v-show="showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-regular fa-eye-slash"></i>
    </label>
    <input class="password" :type="!showPassword ? 'password' : 'text'" name="password" value="" required>

    <button class="submit" type="submit">Update</button>
  </form>
</template>


<script>
  export default {
    props: [
      'customer'
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
