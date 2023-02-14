<template>

  <!-- <h2>{{ process.env.AWS_DEFAULT_REGION }}</h2> -->

  <div class="web-box profile-main">
    <div class="wb-row">
      <ul>
        <li><strong>Title: </strong>{{ this.category.title }}</li>
        <li v-show="this.category.subtitle"><strong>Subtitle: </strong>{{ this.category.subtitle }}</li>
        <li v-show="this.category.description" class="text-box"><strong>Description: </strong>{{ this.category.description }}</li>
        <li v-show="this.category.created_at"><strong>Created At: </strong>{{ this.category.created_at }}</li>
      </ul>
      <div class="wb-image"
      :style="{ backgroundImage: 'url(https://hc-main.s3.eu-west-2.amazonaws.com/assets/' + this.image + ')' }"></div>
    </div>
  </div>

</template>


<script>
  export default {
    props: [
      'category',
      'image',
    ],
  };
</script>
