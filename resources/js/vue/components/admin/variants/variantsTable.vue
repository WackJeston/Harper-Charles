<template>

  <table class="web-box">
    <tr>
      <th id="column1">#</th>
      <th id="column2">Title</th>
      <th id="column3">Active</th>
      <th id="column4">Options</th>
      <th id="column5"></th>
    </tr>

    <tr v-for="(variant, i) in this.variants">
      <td id="column1"><div>{{ variant.id }}</div></td>
      <td id="column2"><div>{{ variant.title }}</div></td>
      <td id="column3">
        <a v-if="variant.show" :href="'/variantShow/' + variant.id + '/0'">
          <i class="fa-solid fa-circle-check primary"></i>
        </a>
        <a v-else :href="'/variantShow/' + variant.id + '/1'">
          <i class="fa-solid fa-circle-xmark non-primary"></i>
        </a>
      </td>
      <td id="column4"><div>{{ variant.children }}</div></td>
      <td id="column5" class="tr-buttons">
        <a :href="'/admin/variant-profile/' + variant.id">
          <i class="fa-solid fa-arrow-up-right-from-square">
            <div class="button-label">
              <p>Manage Variant</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-show="this.variants == false" class="empty-table">
      <h3>No Variants</h3>
    </div>
  </table>

</template>

<script>
export default {
  props: [
    'variants',
  ],

  mounted() {
    document.querySelector("#column1").style.width = "10%";
    document.querySelector("#column2").style.width = "40%";
    document.querySelector("#column3").style.width = "20%";
    document.querySelector("#column4").style.width = "20%";
    document.querySelector("#column5").style.width = "10%";
  },
}
</script>
