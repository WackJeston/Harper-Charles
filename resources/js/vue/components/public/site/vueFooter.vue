<template>
  <footer>

    <nav>
      <a href="/" class="title">
				<img :src="this.publicasset + 'website-title.svg'" alt="this.sitetitle" height="16">
      </a>

      <ul>
        <a v-for="(link, i) in this.publiclinks" :href="link.link" class="nav-link">
          <li>{{ capFL(link.title) }}</li>
        </a>

				<a href="/site-map" class="nav-link">
          <li>Site Map</li>
        </a>
      </ul>

			<ul id="footer-socials">
				<a v-for="(social, i) in this.socials" :href="social.link" target="_blank">
					<li><i :class="social.icon"></i></li>
				</a>
			</ul>
    </nav>

		<a href="/">
			<img defer :src="this.publicasset + 'website-logo.svg'" alt="logo" class="logo">
		</a>

  </footer>
</template>


<script>
  export default {
    props: [
      'sitetitle',
			'publicasset',
      'publiclinks',
			'userlinks',
			'socials',
			'sessionuser',
    ],

    methods: {
      capFL(string) {
			const mySentence = string;
			const words = mySentence.split(" ");

			for (let i = 0; i < words.length; i++) {
				words[i] = words[i][0].toUpperCase() + words[i].substr(1);
			}

			return words.join(" ");
		},
    },
  };
</script>
