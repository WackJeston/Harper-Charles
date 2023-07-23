<template>

  <!-- Buttons -->
  <button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <a :href="'/variant-profileShowVariant/' + this.variant.id + '/0'" v-if="this.variant.show == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/variant-profileShowVariant/' + this.variant.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }" @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'createOption' }" @click="show == 'createOption' ? show = false : show = 'createOption'">Create Option</button>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" :action="'/variant-profileUpdate/' + this.variant.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Variant Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" :value="this.variant.title" required>

    <button class="submit" type="submit">Update</button>
  </form>

  <!-- Options Form -->
  <form class="web-box dk" v-show="show == 'createOption'" :action="'/variant-profileCreateOption/' + this.variant.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = null"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Option Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" required>

    <button class="submit" type="submit">Create</button>
  </form>

  <!-- Options table -->
  <div v-html="this.subvariantstable.html"></div>
</template>


<script>
  export default {
    props: [
      'variant',
      'subvariantstable',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: null,
      };
    },
  };
</script>
