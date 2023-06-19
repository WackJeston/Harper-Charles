<template>
  <table class="web-box">
		<thead>
			<tr>
				<th v-for="(column, i) in this.columns">{{ column.title }}</th>
			</tr>
		</thead>

		<tbody>
			<tr v-for="(row, i) in this.records">
				<td v-for="(column, i) in this.columns"><div>{{ row[column.name] }}</div></td>
				<!-- <td class="tr-buttons">
					<a :href="'/admin/product-profile/' + product.id">
						<i class="fa-solid fa-arrow-up-right-from-square">
							<div class="button-label">
								<p>Manage Product</p>
								<div></div>
							</div>
						</i>
					</a>
				</td> -->
			</tr>
		</tbody>
	</table>
</template>


<script>
  export default {
    props: [
      'table',
    ],

    data() {
			this.columns = this.table.columns;
			this.records = this.table.records;
    },
  };
</script>
