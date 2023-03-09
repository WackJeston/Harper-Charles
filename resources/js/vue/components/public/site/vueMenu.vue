<template>
	<nav class="mobile-nav dk">
		<a href="/" class="title">
			<h2>{{ this.sitetitle }}</h2>
		</a>

		<ul>
			<div v-if="this.sessionuser" class="nav-link">
				<a class="primary-nav-link" @click="toggleLinks(999, true)">
					<i class="fa-regular fa-user nav-link-icon"></i>
					<p>{{ this.sessionuser.firstName + ' ' + this.sessionuser.lastName }}</p>
					<i class="fa-solid fa-angle-down hover-background" :class="'ladown' + 999"></i>
					<i class="fa-solid fa-angle-up hover-background" :class="'laup' + 999"></i>
				</a>

				<ul :class="'sublist' + 999" class="nav-sublinks">
					<a v-for="(sublink, i) in this.userlinks" :href="sublink['link']" class="nav-sublink">
						<li>{{ capFL(sublink['title']) }}</li>
						<i :class="[sublink['icon']]" class="nav-link-icon"></i>
					</a>
				</ul>
			</div>

			<div v-else class="nav-link">
				<a href="/login" class="primary-nav-link">
					<i class="fa-regular fa-user nav-link-icon"></i>
					<p>Login / Sign Up</p>
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
	</nav>
</template>


<script>
export default {
	props: [
		'sitetitle',
		'publiclinks',
		'userlinks',
		'sessionuser',
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
