<template>
	<div class="basket-functions dk" v-if="this.lineCount > 0">
		<strong>{{ this.lineCountQuantity }} items <br><span>|</span> Basket Total: £{{ this.totalPrice.toFixed(2) }}</strong>
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
						<span class="price" :id="'price' + line.id"><strong>Total Price:</strong> £{{ (line.price * line.quantity).toFixed(2) }}</span>
					</div>
				</div>
				<div v-if="line.variants" class="variants-container">
					<span v-for="(variant, i2) in line.variants">{{ variant.parentTitle }}: {{ variant.title }}</span>
				</div>

				<div class="form basket-line-bottom-row">
					<label for="quantity">Quantity</label>
					<input type="number" min="1" name="quantity" :id="'quantity' + line.id" v-model="line.quantity"
						v-on:change="quantityChange(line.id, $event.target.value, line.price)">
					<button type="button" name="remove" class="remove-line" @click="remove(line.id)">
						<i class="fa-solid fa-xmark"></i>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div v-show="(this.lineCount > 4)" class="basket-functions dk">
		<strong>{{ this.lineCountQuantity }} items <br><span>|</span> Basket Total: £{{ this.totalPrice.toFixed(2) }}</strong>
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

		if (this.lines != null) {
			this.lines.forEach(line => {
				this.lineCount++;
				this.lineCountQuantity += line.quantity;
			});
		}
	},

	methods: {
		countLines() {
			this.lineCount = 0;
			this.lineCountQuantity = 0;
			const container = document.getElementById('basketLinesContainer');

			for (let i = 0; i < container.children.length; i++) {
				this.lineCount++;
				this.lineCountQuantity += parseFloat(container.children[i].children[1].children[2].children[1].value);
			}
		},

		total() {
			this.totalPrice = 0;
			const container = document.getElementById('basketLinesContainer');

			let lines = document.querySelectorAll('.basket-line');

			lines.forEach(function(line) {
				console.log(line);
			});

			// for (let i = 0; i < container.children.length; i++) {
			// 	this.totalPrice += parseFloat(container.children[i].children[1].children[0].children[1].innerHTML.replace('£', ''));
			// }
		},

		async quantityChange(id, quantity, price) {
			console.log(id);
			console.log(quantity);
			console.log(price);

			try {
				this.response = await fetch("/basketQuantityUpdate/" + id + "/" + quantity, {
					method: 'POST'
				});
				this.result = await this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				let priceElement = document.querySelector('#price' + id);
				let newPrice = quantity * price;

				priceElement.innerHTML = '£' + newPrice.toFixed(2);

				this.countLines();
				this.total();
			}
		},

		async remove(id) {
			try {
				this.response = await fetch("/basketRemove/" + id);
				this.result = await this.response.json();
				
			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				this.basketLine = document.querySelector('#basketLine' + id);
				this.basketLine.remove();

				this.countLines();
				this.total();
			}
		}
	}
};
</script>
