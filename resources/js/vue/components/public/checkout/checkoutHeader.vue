<template>
	<header>

		<a class="logo-link" href="/">
			<img async :src="this.publicasset + 'website-logo.svg'" alt="logo" class="logo">
		</a>

	</header>
</template>


<script>
export default {
	props: [
		'publicasset',
	],

	mounted() {
		this.setScrollListener();
	},

	methods: {
		setScrollListener() {
			const page = document.querySelector('#page-container');
			const header = document.querySelector('header');

			page.addEventListener('scroll', function() {
				let scrollLimit = window.innerWidth > 640 ? 70 : 55;

				if (page.scrollTop > scrollLimit) {
					header.classList.add('scroll');
				
				} else {
					header.classList.remove('scroll');
				}
			});
		},
	},
};
</script>
