<template>
  <div class="cart-functions web-box dk">
    <strong>{{ this.itemCountQuantity }} items | Total: £{{ this.totalPrice.toFixed(2) }}</strong>
    <a href="/checkout" class="page-button padding">Checkout</a>
  </div>

  <div id="cartItemsContainer">
    <div class="web-box dk cart-item" v-for="(item, i) in this.items" :id="'cartItem' + item.id">
      <a :href="'/product-page/' + item.productId" v-if="item.filename" class="wb-image"
      :style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + item.filename + ')' }"></a>
      <a :href="'/product-page/' + item.productId" v-else class="wb-image">
        <i class="fa-solid fa-couch"></i>
      </a>

      <div class="cart-item-content">
        <div class="cart-item-top-row">
          <h3><a :href="'/product-page/' + item.productId">{{ item.title }}</a></h3>
          <strong class="price" :id="'price' + item.id">£{{ (item.price * item.quantity).toFixed(2) }}</strong>
        </div>
        <div v-if="this.variants[item.id]" class="variants-container">
          <span v-for="(variant, i2) in this.variants[item.id]">{{ variant }}</span>
        </div>

        <div class="form cart-item-bottom-row">
          <label for="quantity">Quantity</label>
          <input type="number" min="1" name="quantity" :id="'quantity' + item.id" v-model="item.quantity" v-on:change="quantityChange(item.id, $event.target.value, item.price)">
          <button type="button" name="remove" class="page-button" @click="remove(item.id)">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div v-show="(this.itemCount > 4)" class="cart-functions web-box dk">
    <strong>{{ this.itemCountQuantity }} items | Total: £{{ this.totalPrice.toFixed(2) }}</strong>
    <a href="/checkout" class="page-button padding">Checkout</a>
  </div>
</template>


<script>
  export default {
    props: [
      'items',
      'variants',
    ],

    data() {
      return {
        totalPrice: 0,
        itemCount: 0,
        itemCountQuantity: 0,
      }
    },

    mounted() {
      this.total();

      this.items.forEach(item => {
        this.itemCount++;
        this.itemCountQuantity += item.quantity;
      });
    },

    methods: {
      countItems() {
        this.itemCount = 0;
        this.itemCountQuantity = 0;
        const container = document.getElementById('cartItemsContainer');
        
        for (let i = 0; i < container.children.length; i++) {
          this.itemCount++;
          this.itemCountQuantity += parseFloat(container.children[i].children[1].children[2].children[1].value);
        }
      },

      total() {
        this.totalPrice = 0;
        const container = document.getElementById('cartItemsContainer');
        
        for (let i = 0; i < container.children.length; i++) {
          this.totalPrice += parseFloat(container.children[i].children[1].children[0].children[1].innerHTML.replace('£', ''));
        }
      },

      async quantityChange(item, quantity, price) {
        try {
          const { data } = await this.$http.post(
            '/apiQuantityUpdate/' + item + '/' + quantity,
            { name: "quantity" }
          );
        } catch (err) {

        } finally {
          let priceElement = document.querySelector('#price' + item);
          let newPrice = quantity * price;

          priceElement.innerHTML = '£' + newPrice.toFixed(2);

          this.countItems();
          this.total();
        }
      },

      async remove(item) {
        try {
          const { data } = await this.$http.get(
            '/cartRemove/' + item,
            { name: "remove" }
          );
        } catch (err) {

        } finally {
          this.cartItem = document.querySelector('#cartItem' + item);
          this.cartItem.remove();

          this.countItems();
          this.total();
        }
      }
    }
  };
</script>
