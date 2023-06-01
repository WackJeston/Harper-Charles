<template>
  <!-- Buttons -->
	<div class="page-button-row">
		<button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  	@click="show == 'edit' ? show = false : show = 'edit'">Edit Account Details</button>

		<button class="page-button" type="button" :class="{ 'button-active' : show == 'orders' }"
  	@click="show == 'orders' ? show = false : show = 'orders'">Orders</button>
	</div>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" :action="'/accountUpdate/' + this.user.id" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="firstname">First Name<span> *</span></label>
    <input type="text" name="firstname" maxlength="100" :value="this.user.firstName" required>

    <label for="lastname">Last Name<span> *</span></label>
    <input type="text" name="lastname" maxlength="100" :value="this.user.lastName" required>

    <label for="email">Email<span> *</span></label>
    <input type="text" name="email" maxlength="100" :value="this.user.email" required>

    <label for="password">Password<span> *</span></label>
    <label for="password" class="show-password">
      <i id="show-password" v-show="!showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-solid fa-eye"></i>
      <i id="hide-password" v-show="showPassword" @click="(this.showPassword = !this.showPassword)" class="fa-regular fa-eye-slash"></i>
    </label>
    <input class="password" :type="!showPassword ? 'password' : 'text'" name="password" value="" required>

    <button class="submit" type="submit">Update</button>
  </form>

	<!-- Orders Table -->
	<table class="web-box" v-show="show == 'orders'">
		<i class="fa-solid fa-xmark" @click="show = false"></i>
		<thead>
			<tr>
				<th width="60">#</th>
				<th>Date</th>
				<th>Value</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(order, i) in this.orders">
				<td width="60">{{ order.id }}</td>
				<td>{{ order.date }}</td>
				<td>£{{ order.total }}</td>
				<td>{{ order.status }}</td>
				<td id="image-column4" class="tr-buttons">
					<a :href="'/account/order/' + order.id">
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

	<div class="web-box dk section-width">
		<ul>
			<li><strong>Name:</strong> {{ this.user.firstName }} {{ this.user.lastName }}</li>
			<li><strong>Email:</strong> {{ this.user.email }}</li>
		</ul>
	</div>
</template>


<script>
  export default {
    props: [
      'user',
			'orders',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
        showPassword: false,
      };
    },

    // methods: {
    //   delete(toggle = 1) {
    //     if (toggle == 1) {
    //       document.querySelector('.warning-overlay').style.display = 'flex';
    //     } else {
    //       document.querySelector('.warning-overlay').style.display = 'none';
    //     }
    //   },
    // },
  };
</script>
