<template>
	<div class="basket-functions dk" v-if="this.lineCount > 0">
		<strong>{{ this.lineCountQuantity }} Items <br class="hide-desktop"><span class="hide-mobile">|</span> Basket Total: £{{ this.totalPrice }}</strong>
		<a href="/checkout/address" class="page-button padding"><span class="hide-mobile">Proceed To </span> Checkout <i class="fa-solid fa-angles-right"></i></a>
	</div>

	<div id="basketLinesContainer">
		<div class="dk basket-line" v-for="(line, i) in this.lines" :id="'basketLine' + line.id">
			<a :href="'/product/' + line.productId" v-if="line.fileName">
				<img :src="line.fileName" :alt="line.title">
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
						<span class="price"><strong>Price:</strong> £{{ line.price }}</span>
						<span class="price" :id="'price' + line.id"><strong>Total:</strong> £{{ parseFloat(line.price * line.quantity).toFixed(2) }}</span>
					</div>
				</div>

				<div class="form basket-line-bottom-row">
					<input type="number" min="1" :max="line.max" name="quantity" :id="'quantity' + line.id" v-model="line.quantity" v-on:change="quantityChange(line.id, $event.target.value)">

					<small class="remove-line" @click="remove(line.id)">Remove</small>
				</div>
				
				<div v-if="line.variants.length > 0" class="variants-container">
					<span v-for="(variant, i2) in line.variants">{{ variant.parentTitle }}: {{ variant.title }}</span>
				</div>
			</div>
		</div>
	</div>

	<div v-show="(this.lineCount > 3)" class="basket-functions dk">
		<strong>{{ this.lineCountQuantity }} Items <br class="hide-desktop"><span class="hide-mobile">|</span> Basket Total: £{{ this.totalPrice }}</strong>
		<a href="/checkout/addresses" class="page-button padding"><span class="hide-mobile">Proceed To </span> Checkout <i class="fa-solid fa-angle-right"></i></a>
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
		this.total(true);
	},

	methods: {
		total(start = false) {
			let basketLinks = document.querySelectorAll('a[href="/basket"] .basket-count');
			let lineElements = document.querySelectorAll('.basket-line');
			let lines = this.lines;
			let price = 0;
			let count = 0;
			let quantity = 0;

			if (start && lineElements.length == 0) {
				location.reload();
			}

			lineElements.forEach(function(lineElement, index) {
				let quantityTemp = lineElement.querySelector('input[name="quantity"]').value;
				let priceTemp = lines[index].price;

				price += parseFloat(priceTemp * quantityTemp);
				count++;
				quantity++;
			});
			
			basketLinks.forEach(function(basketLink) {
				basketLink.innerHTML = quantity;
			});
			
			this.lineCount = count;
			this.lineCountQuantity = quantity;
			this.totalPrice = price;
		},

		async quantityChange(id, quantity) {
			try {
				this.response = await fetch("/basketQuantityUpdate/" + id + "/" + quantity);
				this.result = await this.response.json();

			} catch (err) {
				console.log('----ERROR----');
				console.log(err);
				
			} finally {
				if (!this.result) {
					this.basketLine = document.querySelector('#basketLine' + id);
					this.basketLine.remove();
				}

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

				this.total();
			}
		}
	}
};
</script>
