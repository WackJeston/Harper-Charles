<template>
  <table class="web-box">
		<thead>
			<tr>
				<th v-for="(column, i) in this.columns" :style="[column.name == 'id' ?? {maxWidth: '50px'}]">{{ column.title }}</th>
			</tr>
		</thead>

		<tbody>
			<tr v-for="(record, i) in this.records">
				<td v-for="(column, i2) in this.columns" :style="[column.name == 'id' ?? {maxWidth: '50px'}]">{{ record[column.name] }}</td>
				<td v-if="this.buttons.length >= 1" class="tr-buttons">
					<a v-for="(button, i3) in this.buttons" :href="record.buttonLinks[i3]">
						<i :class="button.icon">
							<div v-if="button.label != null" class="button-label">
								<p>{{ button.label }}</p>
								<div></div>
							</div>
						</i>
					</a>
				</td>
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
			this.buttons = this.table.buttons;
    },

		mounted() {
			this.getColumnWidth();
		},

		methods: {
			getColumnWidth() {
				let buttonsWidth = document.querySelector('.tr-buttons').offsetWidth + 50;
				let columnWidthCount = 0;

				this.columns.forEach(column => {
					if (column.name != 'id') {
						columnWidthCount += column.width;
					}
				});

				this.columns.forEach(column => {
					if (column.name != 'id') {
						column.newWidth = 'calc(' + Math.floor((100 / columnWidthCount) * column.width) + '% - ' + Math.floor((buttonsWidth / columnWidthCount) * column.width) + 'px)';
						console.log(column.newWidth);
					}
				});
			}
		}
  };
</script>
