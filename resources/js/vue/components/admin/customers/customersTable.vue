<template>
  <table class="web-box">
    <tr>
      <th id="column1">#</th>
      <th id="column2">Name</th>
      <th id="column3">Email</th>
      <th id="column4" class="hide-mobile">Created At</th>
      <th id="column5"></th>
    </tr>

    <tr v-for="(customer, i) in this.customers">
      <td id="column1"><div>{{ customer.id }}</div></td>
      <td id="column2"><div>{{ customer.name }}</div></td>
      <td id="column3"><div>{{ customer.email }}</div></td>
      <td id="column4" class="hide-mobile"><div>{{ customer.created_at }}</div></td>
      <td id="column5" class="tr-buttons">
        <a :href="'/admin/customer-profile/' + customer.id">
          <i class="fa-solid fa-arrow-up-right-from-square">
            <div class="button-label">
              <p>Manage Customer</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-show="this.customers == false" class="empty-table">
      <h3>No Customers</h3>
    </div>

  </table>
</template>


<script>
  export default {
    props: [
      'customers',
    ],

    methods: {
      hideMobile() {
        if (window.innerWidth < 650) {
          document.querySelector("#column1").style.width = "10%";
          document.querySelector("#column2").style.width = "35%";
          document.querySelector("#column3").style.width = "45%";
          document.querySelector("#column5").style.width = "10%";
        } else {
          document.querySelector("#column1").style.width = "10%";
          document.querySelector("#column2").style.width = "25%";
          document.querySelector("#column3").style.width = "30%";
          document.querySelector("#column4").style.width = "25%";
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
  };
</script>
