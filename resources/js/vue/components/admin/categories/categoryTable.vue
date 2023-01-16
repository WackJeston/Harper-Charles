<template>

  <table class="web-box">
    <tr>
      <th id="column1">#</th>
      <th id="column2">Title</th>
      <th id="column3">Show/Hide</th>
      <th id="column4" class="hide-mobile">Products</th>
      <th id="column5"></th>
    </tr>

    <tr v-for="(category, i) in this.categories">
      <td id="column1"><div>{{ category.id }}</div></td>
      <td id="column2"><div>{{ category.title }}</div></td>
      <td id="column3">
        <a v-if="category.show" :href="'/categoryShow/' + category.id + '/0'">
          <i class="fa-solid fa-circle-check primary"></i>
        </a>
        <a v-else :href="'/categoryShow/' + category.id + '/1'">
          <i class="fa-solid fa-circle-xmark non-primary"></i>
        </a>
      </td>
      <td id="column4" class="hide-mobile"><div>{{ category.productCount }}</div></td>
      <td id="column5" class="tr-buttons">
        <a :href="'/admin/category-profile/' + category.id">
          <i class="fa-solid fa-arrow-up-right-from-square">
            <div class="button-label">
              <p>Manage Category</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-show="this.categories == false" class="empty-table">
      <h3>No Categories</h3>
    </div>

  </table>

</template>

<script>
export default {
  props: [
    'categories',
    'joins',
    'products',
  ],

  methods: {
    hideMobile() {
      if (window.innerWidth < 650) {
        document.querySelector("#column1").style.width = "10%";
        document.querySelector("#column2").style.width = "50%";
        document.querySelector("#column3").style.width = "20%";
        document.querySelector("#column5").style.width = "20%";
      } else {
        document.querySelector("#column1").style.width = "10%";
        document.querySelector("#column2").style.width = "35%";
        document.querySelector("#column3").style.width = "20%";
        document.querySelector("#column4").style.width = "20%";
        document.querySelector("#column5").style.width = "10%";
      }
    },
  },

  mounted() {
    this.hideMobile();
  },

  created() {
    window.addEventListener('resize', this.hideMobile);
  }
}
</script>
