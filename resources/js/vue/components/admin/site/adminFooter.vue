<template>
  <footer class="lt">

    <!-- <nav class="footer-nav">
      <a href="/" class="title"><h2 class="hover hover-underline from-left">{{ this.sitetitle }}</h2></a>

      <ul>
        <a v-for="(link, i) in this.publiclinks" :href="link" class="nav-link hover hover-underline from-left"><li>{{ this.publiclinkscaps[i] }}</li></a>
      </ul>
    </nav> -->

  </footer>
</template>


<script>
  export default {
    props: [
      'sitetitle',
      'publiclinks',
      'publiclinkscaps',
    ],
  };
</script>
