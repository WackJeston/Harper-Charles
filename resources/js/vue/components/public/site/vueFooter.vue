<template>
  <footer class="dk">

    <nav class="footer-nav">
      <a href="/" class="title">
        <h2>{{ this.sitetitle }}</h2>
      </a>

      <ul>
        <a v-for="(link, i) in this.publiclinks" :href="link.link" class="nav-link">
          <li>{{ capFL(link.title) }}</li>
        </a>
      </ul>
    </nav>

  </footer>
</template>


<script>
  export default {
    props: [
      'sitetitle',
      'publiclinks',
    ],

    methods: {
      capFL(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
    },
  };
</script>
