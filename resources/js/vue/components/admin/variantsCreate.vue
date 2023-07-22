<template>

  <button class="page-button" type="button" :class="{ 'button-active' : showCreate == true }"
  @click="showCreate = !showCreate">Add Variant</button>

  <form class="web-box" v-show="showCreate" action="/variantCreate" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="showCreate = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" required>

    <button class="submit" type="submit">Create</button>
  </form>

</template>

<script>
export default {
  data() {
    return {
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      showCreate: false,
    };
  },
}
</script>
