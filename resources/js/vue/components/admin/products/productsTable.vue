<template>
  <table class="web-box">
		<thead>
			<tr>
				<th id="column1">#</th>
				<th id="column2">Title</th>
				<th id="column3" class="hide-mobile">Subtitle</th>
				<th id="column4">Product #</th>
				<th id="column5"></th>
			</tr>
		</thead>

		<tbody>
			<tr v-for="(product, i) in this.products">
				<td id="column1"><div>{{ product.id }}</div></td>
				<td id="column2"><div>{{ product.title }}</div></td>
				<td id="column3" class="hide-mobile"><div>{{ product.subtitle }}</div></td>
				<td id="column4"><div>{{ product.productNumber }}</div></td>
				<td id="column5" class="tr-buttons">
					<a :href="'/admin/product-profile/' + product.id">
						<i class="fa-solid fa-arrow-up-right-from-square">
							<div class="button-label">
								<p>Manage Product</p>
								<div></div>
							</div>
						</i>
					</a>
				</td>
			</tr>
		</tbody>

		<tfoot>
			<nav>
				<ul class="pagination-controls">
					<li>Previous</li>
					<li>Next</li>
				</ul>
			</nav>
		</tfoot>

    <div v-show="this.products == false" class="empty-table">
      <h3>No Products</h3>
    </div>

  </table>
</template>


<script>
  export default {
    props: [
      'products',
    ],

    methods: {
      hideMobile() {
        if (window.innerWidth < 650) {
          document.querySelector("#column1").style.width = "10%";
          document.querySelector("#column2").style.width = "40%";
          document.querySelector("#column4").style.width = "30%";
          document.querySelector("#column5").style.width = "20%";
        } else {
          document.querySelector("#column1").style.width = "10%";
          document.querySelector("#column2").style.width = "30%";
          document.querySelector("#column3").style.width = "30%";
          document.querySelector("#column4").style.width = "15%";
          document.querySelector("#column5").style.width = "15%";
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
