<template>
	<header>

		<nav id="header-start">
			<a v-for="(link, i) in this.publiclinks" :href="link.link" class="header-desktop">
				<i class="header-desktop" :class="link.icon"></i>
				<span class="header-desktop">{{ link.title }}</span>
			</a>
		</nav>

		<a class="logo-link" href="/">
			<img async :src="this.publicasset + 'website-logo.svg'" alt="logo" class="logo">
		</a>

		<nav id="header-end">
			<!-- <ul id="header-socials" class="header-desktop">
				<a v-for="(social, i) in this.socials" :href="social.link" target="_blank">
					<li><i :class="social.icon"></i></li>
				</a>
			</ul> -->

			<a v-if="this.sessionuser" href="/account" id="login" class="header-desktop">
				<i class="fa-solid fa-user header-desktop"></i>
				<span class="header-desktop">Account</span>
			</a>

			<a v-else href="/login" id="login" class="header-desktop">
				<i class="fa-regular fa-user header-desktop"></i>
				<span class="header-desktop">Sign In</span>
			</a>

			<i @click='toggleSiteMenu' class="fa-solid fa-bars header-mobile" id="nav-menu-button"></i>
		</nav>

		<div class="menu-overlay" @click="toggleOverlay"></div>

	</header>
</template>


<script>
export default {
	props: [
		'sitetitle',
		'sitetitlemini',
		'publicasset',
		'publiclinks',
		'userlinks',
		'socials',
		'sessionuser'
	],

	data() {
		return {
			mobile: null,
			siteMenu: false,
			windowWidth: null,
			menuActive: false,
			mqLarge: null,
			sublinksActive: false,
			userMenuActive: false,
		}
	},

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

		toggleSiteMenu() {
			this.siteMenu = !this.siteMenu;
			let menu = document.querySelector(".site-menu");
			let overlay = document.querySelector(".menu-overlay");

			if (this.menuActive == false) {
				menu.classList.remove("menu-non-active");
				menu.classList.add("menu-active");

				overlay.style.display = "block";
				overlay.style.opacity = 0.6;

				this.menuActive = true;

			} else {
				menu.classList.remove("menu-active");
				menu.classList.add("menu-non-active");

				overlay.style.display = "none";
				overlay.style.opacity = 0;
			}
		},

		toggleOverlay() {
			this.siteMenu = false;
			this.menuActive = false;
			let menu = document.querySelector(".site-menu");
			let overlay = document.querySelector(".menu-overlay");

			menu.classList.remove("menu-active");
			menu.classList.add("menu-non-active");

			overlay.style.display = "none";
			overlay.style.opacity = 0;

			return;
		},

		capFL(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
	},
};
</script>
