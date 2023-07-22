<template>

  <div class="web-box profile-main">
    <div class="wb-row">
      <ul>
        <li><strong>Title: </strong>{{ this.product.title }}</li>
        <li><strong>Subtitle: </strong>{{ this.product.subtitle }}</li>
        <li class="text-box"><strong>Description: </strong>{{ this.product.description }}</li>
        <li><strong>Product Number: </strong>{{ this.product.productNumber }}</li>
        <li><strong>Price: </strong>£{{ this.product.price }}</li>
        <li v-show="this.product.created_at"><strong>Created At: </strong>{{ this.product.created_at }}</li>
      </ul>
      <div class="wb-image" v-show="this.image"
      :style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + this.image + ')' }"></div>
    </div>
  </div>

</template>


<script>
  export default {
    props: [
      'product',
      'image',
    ],
  };
</script>
