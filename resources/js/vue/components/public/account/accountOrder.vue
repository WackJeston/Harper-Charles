<template>
  <!-- Buttons -->
	<div class="page-button-row">
		<button class="page-button" type="button" :class="{ 'button-active' : show == 'notes' }"
  	@click="show == 'notes' ? show = false : show = 'notes'">Order Notes</button>

		<a :href="'https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + this.invoice.fileName" target="_blank" class="page-button padding">
			Invoice
			<i class="fa-solid fa-file-invoice"></i>
		</a>
	</div>

  <div class="web-box dk section-width">
		<ul>
			<li><strong>Order:</strong> #{{ this.order.id }}</li>
			<li><strong>Date:</strong> {{ this.order.date }}</li>
			<li><strong>Status:</strong> {{ this.order.status }}</li>
		</ul>
	</div>

	<table class="web-box">
		<thead>
			<tr>
				<th width="80"></th>
				<th>Title</th>
				<th>Qty</th>
				<th>Price</th>
				<th>Total</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(line, i) in this.order.lines">
				<td width="80"><img width="60" :src="'https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + line.fileName" :alt="line.title"></td>
				<td>{{ line.title }}</td>
				<td>{{ line.quantity }}</td>
				<td>£{{ line.price }}</td>
				<td>£{{ (line.price * line.quantity).toFixed(2) }}</td>
				<td id="image-column4" class="tr-buttons">
					<a :href="'/product-page/' + line.id">
						<i class="fa-solid fa-folder-open">
							<div class="button-label">
								<p>Open Record</p>
								<div></div>
							</div>
						</i>
					</a>
				</td>
			</tr>
		</tbody>

		<div v-show="this.orders == false" class="empty-table">
			<h3>No Orders</h3>
		</div>
	</table>

	<!-- Notes Form -->
  <form class="web-box dk" v-show="show == 'notes'" :action="'/accountUpdate/' + this.user.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="firstname">Note<span> *</span></label>
    <textarea type="text" name="note" maxlength="4000"></textarea>

    <button class="submit" type="submit">Submit</button>
  </form>

	<!-- Notes Table -->
	<table class="web-box" v-show="this.show == 'notes'">
		<thead>
			<tr>
				<th width="60">#</th>
				<th>Note</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(note, i) in this.notes">
				<td width="60">{{ note.id }}</td>
				<td>{{ note.note }}</td>
			</tr>
		</tbody>

		<div v-show="this.notes == false" class="empty-table">
			<h3>No Notes</h3>
		</div>
	</table>
</template>


<script>
  export default {
    props: [
      'user',
			'order',
			'invoice',
			'notes',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
      };
    },
  };
</script>