<template>

  <!-- Buttons -->
  <button @click="this.delete()" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
  <a :href="'/variant-profileShowVariant/' + this.variant.id + '/0'" v-if="this.variant.show == 1"><button class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button></a>
	<a :href="'/variant-profileShowVariant/' + this.variant.id + '/1'" v-else><button class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button></a>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'createOption' }"
  @click="show == 'createOption' ? show = false : show = 'createOption'">Create Option</button>

  <!-- Delete Warning -->
  <div @click="this.delete(0)" class="warning-overlay">
    <div class="web-box warning-box dk">
      <h3 class="warning">WARNING</h3>
      <p>This will permanently delete <strong>Variant #{{ this.variant.id }}</strong></p>
      <div class="row">
        <a :href="'/variant-profileDelete/' + this.variant.id"><button type="button" name="delete" class="delete">Delete</button></a>
        <button @click="this.delete(0)" type="button" name="cancel" class="cancel">Cancel</button>
      </div>
    </div>
  </div>

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
  <table class="web-box">
    <tr>
      <th id="column1">#</th>
      <th id="column2">Title</th>
      <th id="column3">Active</th>
      <th id="column4"></th>
    </tr>

    <tr v-for="(sub, i) in this.subvariants">
      <td id="column1"><div>{{ sub.id }}</div></td>
      <td id="column2"><div>{{ sub.title }}</div></td>
      <td id="column3">
        <a v-if="sub.show" :href="'/variant-profileShowOption/' + this.variant.id + '/' + sub.id + '/0'">
          <i class="fa-solid fa-circle-check primary"></i>
        </a>
        <a v-else :href="'/variant-profileShowOption/' + this.variant.id + '/' + sub.id + '/1'">
          <i class="fa-solid fa-circle-xmark non-primary"></i>
        </a>
      </td>
      <td id="column4" class="tr-buttons">
        <a :href="'/variant-profileDeleteOption/' + this.variant.id + '/' + sub.id">
          <i class="fa-solid fa-trash-can">
            <div class="button-label">
              <p>Delete Option</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-show="this.subvariants == false" class="empty-table">
      <h3>No Options</h3>
    </div>
  </table>
</template>


<script>
  export default {
    props: [
      'variant',
      'subvariants',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: null,
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

    mounted() {
      document.querySelector("#column1").style.width = "15%";
      document.querySelector("#column2").style.width = "50%";
      document.querySelector("#column3").style.width = "15%";
      document.querySelector("#column4").style.width = "20%";
    },
  };
</script>
