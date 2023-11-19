<template>
	<nav class="site-menu lt">
		<a id="menu-home-link" href="/">
			<img defer :src="this.publicasset + 'website-logo-white.svg'" alt="logo" class="logo" height="70">
		</a>

		<ul id="menu-items">
			<div v-if="this.sessionuser" class="nav-link user-nav-link">
				<a class="primary-nav-link" @click="toggleLinks(999, true)">
					<!-- <i class="fa-regular fa-user nav-link-icon"></i> -->
					<p>{{ this.sessionuser.firstName + ' ' + this.sessionuser.lastName }}</p>
					<i class="fa-solid fa-angle-down hover-background" id="ladown999"></i>
					<i class="fa-solid fa-angle-up hover-background" id="laup999"></i>
				</a>

				<ul id="sublist999" class="nav-sublinks">
					<a v-for="(sublink, subi) in this.userlinks" :href="sublink['link']" class="nav-sublink">
						<li>{{ capFL(sublink['title']) }}</li>
						<!-- <i :class="[sublink['icon']]" class="nav-link-icon"></i> -->
					</a>
				</ul>
			</div>

			<div v-else class="nav-link user-nav-link">
				<a class="primary-nav-link" href="/login">
					<!-- <i class="fa-solid fa-user nav-link-icon"></i> -->
					<li>Register | Sign In</li>
				</a>
			</div>

			<div v-for="(link, i) in this.publiclinks" class="nav-link">
				<a v-if="!link['sublink']" class="primary-nav-link" :href="link['link']">
					<!-- <i :class="[link['icon']]" class="nav-link-icon"></i> -->
					<li>{{ capFL(link['title']) }}</li>
				</a>

				<a v-if="link['sublink']" class="primary-nav-link" @click="toggleLinks(i)" v-show="link['sublink']">
					<!-- <i :class="[link['icon']]" class="nav-link-icon"></i> -->
					<li>{{ capFL(link['title']) }}</li>
					<i class="fa-solid fa-angle-down" :id="'ladown' + [i]"></i>
					<i class="fa-solid fa-angle-up" :id="'laup' + [i]"></i>
				</a>

				<ul v-if="link['sublink']" :id="'sublist' + [i]" class="nav-sublinks">
					<a v-for="(sublink, subi) in link['sublink']" :href="sublink['link']" class="nav-sublink">
						<li>{{ capFL(sublink['title']) }}</li>
						<!-- <i :class="[sublink['icon']]" class="nav-link-icon"></i> -->
					</a>
				</ul>
			</div>
		</ul>

		<ul id="menu-socials">
			<a v-for="(social, i) in this.socials" :href="social.link" target="_blank">
				<li><i :class="social.icon"></i></li>
			</a>
		</ul>
	</nav>
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
		toggleLinks(i, usermenu = false) {
			let list = document.querySelector("#sublist" + i);
			let laDown = document.querySelector("#ladown" + i);
			let laUp = document.querySelector("#laup" + i);

			if (list.style.maxHeight == "") {
				let height = 0;
				if (usermenu == false) {
					height = 40 * this.publiclinks[i]['sublink'].length;
				} else {
					height = 40 * list.childElementCount;
				}
				list.style.maxHeight = height + "px";
				laDown.style.display = "none";
				laUp.style.display = "flex";
				return;
			} else {
				list.style.maxHeight = "";
				laUp.style.display = "none";
				laDown.style.display = "flex";
				return;
			}
		},

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
