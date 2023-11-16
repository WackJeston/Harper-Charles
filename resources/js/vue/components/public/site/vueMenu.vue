<template>
	<nav class="site-menu lt">
		<a id="menu-home-link" href="/">
			<img defer :src="this.asset + 'logo-white.png'" alt="logo" class="logo">
		</a>

		<ul id="menu-items">
			<div v-if="this.tickets" class="nav-link">
				<a id="menu-ticket-button" href="https://ipswichfireworks.ticketsrv.co.uk/" target="_blank">
					<i class="fa-solid fa-ticket nav-link-icon"></i>
					<li class="title thick-title">TICKETS</li>
				</a>
			</div>

			<div v-else class="nav-link">
				<a id="menu-ticket-button" href="/">
					<i class="fa-solid fa-house-chimney nav-link-icon"></i>
					<li class="title thick-title">Home</li>
				</a>
			</div>

			<div v-for="(link, i) in this.publiclinks" class="nav-link">
				<a v-if="!link['sublink']" class="primary-nav-link" :href="link['link']">
					<i :class="[link['icon']]" class="nav-link-icon"></i>
					<li>{{ capFL(link['title']) }}</li>
				</a>

				<a v-if="link['sublink']" class="primary-nav-link" @click="toggleLinks(i)" v-show="link['sublink']">
					<i :class="[link['icon']]" class="nav-link-icon"></i>
					<li>{{ capFL(link['title']) }}</li>
					<i class="fa-solid fa-angle-down" :class="'ladown' + [i]"></i>
					<i class="fa-solid fa-angle-up" :class="'laup' + [i]"></i>
				</a>

				<ul v-if="link['sublink']" :class="'sublist' + [i]" class="nav-sublinks">
					<a v-for="(sublink, subi) in link['sublink']" :href="sublink['link']" class="nav-sublink">
						<li>{{ capFL(sublink['title']) }}</li>
						<i :class="[sublink['icon']]" class="nav-link-icon"></i>
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
		'asset',
		'publiclinks',
		'socials',
		'sessionuser',
		'tickets',
	],

	methods: {
		toggleLinks(i, usermenu = false) {
			let list = document.querySelector(".sublist" + i);
			let laDown = document.querySelector(".ladown" + i);
			let laUp = document.querySelector(".laup" + i);
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
