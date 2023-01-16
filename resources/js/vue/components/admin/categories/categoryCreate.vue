<template>

  <button class="page-button" type="button" :class="{ 'button-active' : showCreate == true }"
  @click="showCreate = !showCreate">Add Category</button>

  <form class="web-box" v-show="showCreate" action="/categoryCreate" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="showCreate = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="title">Title<span> *</span></label>
    <input type="text" name="title" maxlength="100" required>

    <label for="subtitle">Subtitle</label>
    <input type="text" name="subtitle" maxlength="100">

    <label for="description">Description</label>
    <textarea type="text" name="description" maxlength="1000"></textarea>

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
