<template>
	<div class="basket-functions dk" v-if="this.lineCount > 0">
		<strong>{{ this.lineCountQuantity }} items <br><span>|</span> Basket Total: £{{ this.totalPrice }}</strong>
		<a href="/checkout/addresses" class="page-button padding">Proceed To Checkout</a>
	</div>

	<div id="basketLinesContainer">
		<div class="dk basket-line" v-for="(line, i) in this.lines" :id="'basketLine' + line.id">
			<a :href="'/product/' + line.productId" v-if="line.fileName">
				<img :src="line.fileName" :alt="line.title" class="wb-image">
			</a>
			<a :href="'/product/' + line.productId" v-else class="wb-image">
				<i class="fa-solid fa-couch"></i>
			</a>

			<div class="basket-line-content">
				<div class="basket-line-top-row">
					<a :href="'/product/' + line.productId">
						<h2>{{ line.title }}</h2>
						<small>#{{ line.productId }}</small>
					</a>
					<div class="line-price-container">
						<span class="price"><strong>Item Price:</strong> £{{ line.price }}</span>
						<span class="price" :id="'price' + line.id"><strong>Total Price:</strong> £{{ parseFloat(line.price * line.quantity).toFixed(2) }}</span>
					</div>
				</div>
				<div v-if="line.variants" class="variants-container">
					<span v-for="(variant, i2) in line.variants">{{ variant.parentTitle }}: {{ variant.title }}</span>
				</div>

				<div class="form basket-line-bottom-row">
					<label for="quantity">Quantity</label>
					<input type="number" min="1" name="quantity" :id="'quantity' + line.id" v-model="line.quantity"
						v-on:change="quantityChange(line.id, $event.target.value)">
					<button type="button" name="remove" class="remove-line" @click="remove(line.id)">
						<i class="fa-solid fa-xmark"></i>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div v-show="(this.lineCount > 4)" class="basket-functions dk">
		<strong>{{ this.lineCountQuantity }} items <br><span>|</span> Basket Total: £{{ this.totalPrice }}</strong>
		<a href="/checkout/addresses">Proceed To Checkout</a>
	</div>
</template>


<script>
export default {
	props: [
		'basket',
	],

	data() {
		return {
			lines: this.basket.lines,
			totalPrice: 0,
			lineCount: 0,
			lineCountQuantity: 0,
		}
	},

	mounted() {
		this.total();
	},

	methods: {
		total() {
			let lines = document.querySelectorAll('.basket-line');
			let price = 0;
			let count = 0;
			let quantity = 0;

			lines.forEach(function(line) {
				let quantityTemp = line.querySelector('input[name="quantity"]').value;
				let priceTemp = line.querySelector('.price').innerHTML.split('£')[1];

				price += parseFloat(priceTemp * quantityTemp);
				count++;
				quantity += parseFloat(quantityTemp);
			});
			
			this.lineCount = count;
			this.lineCountQuantity = quantity;
			this.totalPrice = price;
		},

		async quantityChange(id, quantity) {
			try {
				this.response = await fetch("/basketQuantityUpdate/" + id + "/" + quantity);
				this.result = this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				this.total();
			}
		},

		async remove(id) {
			try {
				this.response = await fetch("/basketRemove/" + id);
				this.result = this.response.json();
				
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				this.basketLine = document.querySelector('#basketLine' + id);
				this.basketLine.remove();

				this.total();
			}
		}
	}
};
</script>
