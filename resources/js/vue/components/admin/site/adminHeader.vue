<template>
  <header class="lt">
    <nav class="desktop-nav">
      <!-- <a href="/admin/dashboard" class="title"><h2 class="hover">{{ this.sitetitle }}</h2></a> -->
      <a v-show="this.showhome == 'false'" href="/admin/dashboard" class="nav-button home-button header-button"><i class="fa-solid fa-house-chimney"></i></a>
      <i v-show="this.showhome == 'true'" class="fa-solid fa-house-chimney nav-button home-button header-button" id="non-active"></i>

			<div id="notification-header-container">
        <div @click="this.navMenuActive = (this.navMenuActive == 'notification' ? null : 'notification')" class="nav-button" id="notification-button">
          <i class="fa-solid fa-bell"></i>
        </div>
      </div>

      <div id="user-header-container">
        <div @click="this.navMenuActive = (this.navMenuActive == 'user' ? null : 'user')" class="header-button" id="user-button">
          <p>{{ this.sessionuser.firstName }} {{ this.sessionuser.lastName }}</p>
          <i class="fa-solid fa-user"></i>
        </div>
      </div>

      <i @click='menuActive = !menuActive' class="fa-solid fa-bars hover-background nav-button" id="menu-button"></i>
    </nav>

		<div id="notification-menu" :style="[this.navMenuActive == 'notification' ? { transform: 'translate3d(0, 100%, 0)', minWidth: this.notificationMenuWidth + 'px' } : { transform: 'translate3d(0, 0, 0)', minWidth: this.notificationMenuWidth + 'px' }]">
      <div class="notification-group" v-for="(group, groupName) in this.notificationsData">
				<h3>{{ groupName }}</h3>
				<div v-for="(notification, i) in group">
					<i v-if="notification.email" :id="'notification-' + notification.id" class="fa-solid fa-square-check" @click="this.toggleNotification(notification.notificationUserId, 'email', notification.id)"></i>
					<i v-else :id="'notification-' + notification.id" class="fa-solid fa-square-xmark" @click="this.toggleNotification(0, 'email', notification.id)"></i>
					<span>{{ notification.name }}</span>
				</div>
			</div>
    </div>

    <div id="user-menu" :style="[this.navMenuActive == 'user' ? { transform: 'translate3d(0, 100%, 0)', minWidth: this.userMenuWidth + 'px' } : { transform: 'translate3d(0, 0, 0)', minWidth: this.userMenuWidth + 'px' }]">
      <a :href="'/admin/user-profile/' + this.sessionuser.id">My Profile</a>
      <a href="/adminLogout">Log Out</a>
    </div>

    <div class="menu-overlay" @click="menuActive = false" v-show="menuActive"></div>
  </header>

  <nav class="admin-menu lt"
  :class="{ 'menu-active': menuActive, 'menu-non-active': !menuActive }">
    <h2 class="title">Admin Console</h2>

    <ul>
      <div v-for="(link, i) in this.adminlinks" class="nav-link">
        <a v-show="!link['sublink']" class="primary-nav-link" :href="link['link']">
          <i :class="[link['icon']]" class="nav-link-icon"></i>
          <li>{{ capFL(link['title']) }}</li>
        </a>

        <a v-show="link['sublink']" class="primary-nav-link">
          <i :class="[link['icon']]" class="nav-link-icon"></i>
          <li>{{ capFL(link['title']) }}</li>
          <i @click="toggleLinks(i, open = false)" v-show="link['sublink']" class="fa-solid fa-angle-down hover-background" :class="'ladown' + [i]"></i>
          <i @click="toggleLinks(i, open = true)" v-show="link['sublink']" class="fa-solid fa-angle-up hover-background" :class="'laup' + [i]"></i>
        </a>

        <ul :class="'sublist' + [i]" class="nav-sublinks">
          <a v-for="(sublink, subi) in link['sublink']" :href="sublink['link']" class="nav-sublink hover-background">
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
      'adminlinks',
      'showhome',
      'sessionuser',
			'notifications',
    ],

    data() {
      return {
        menuActive: false,
        navMenuActive: false,
				userMenuWidth: 0,
				notificationMenuWidth: 0,
				notificationsData: this.notifications,
      };
    },

		mounted() {
			this.setUserMenuWidth();
			this.setNotificationMenuPosition();
		},

    methods: {
			setUserMenuWidth(start = true) {
				let userButton = document.querySelector("#user-header-container");
				this.userMenuWidth = userButton.offsetWidth;

				if (start) {
					setTimeout(() => {
						this.setUserMenuWidth(false);
					}, 500);
					
					setTimeout(() => {
						this.setUserMenuWidth(false);
					}, 5000);
					
					setTimeout(() => {
						this.setUserMenuWidth(false);
					}, 10000);
				}
			},

			setNotificationMenuPosition(start = true) {
				let menu = document.querySelector("#notification-menu");
				let button = document.querySelector("#notification-button");
				let buttonPosition = button.getBoundingClientRect();

				menu.style.right = (window.innerWidth - buttonPosition.left - button.offsetWidth) + "px";

				if (start) {
					setTimeout(() => {
						this.setNotificationMenuPosition(false);
					}, 500);
					
					setTimeout(() => {
						this.setNotificationMenuPosition(false);
					}, 5000);
					
					setTimeout(() => {
						this.setNotificationMenuPosition(false);
					}, 10000);
				}
			},

      toggleLinks(i, open) {
        if(open == false) {
          let list = document.querySelector(".sublist" + i);
          let laDown = document.querySelector(".ladown" + i);
          let laUp = document.querySelector(".laup" + i);
          let height = 30 * this.adminlinks[i]['sublink'].length;
          list.style.maxHeight = height + "px";
          laDown.style.display = "none";
          laUp.style.display = "flex";
          return;
        } else if (open == true) {
          let list = document.querySelector(".sublist" + i);
          let laDown = document.querySelector(".ladown" + i);
          let laUp = document.querySelector(".laup" + i);
          list.style.maxHeight = "0px";
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

			// AJAX
			async toggleNotification(notificationUserId, type, id) {
				try {
					this.response = await fetch("/header-toggleNotification/" + id + "/" + notificationUserId + "/" + type);
					this.result = this.response.json();
					
				} catch (err) {
					console.log('----ERROR----');
					console.log(err);

				} finally {
					// let button = document.querySelector("#notification-" + id);

					if (this.result[0]) {
						this.notificationsData[this.result[1]].forEach(notificationItem => {
							if (notificationItem.name == this.result[2]) {
								notificationItem.notificationUserId = this.result[3];

								if (type == "email") {
									notificationItem.email = 1
								} else if (type == "phone") {
									notificationItem.phone = 1
								} else {
									notificationItem.standard = 1
								}
							}
						});

					} else {
						this.notificationsData[this.result[1]].forEach(notificationItem => {
							if (notificationItem.name == this.result[2]) {
								notificationItem.notificationUserId = undefined;

								if (type == "email") {
									notificationItem.email = 0
								} else if (type == "phone") {
									notificationItem.phone = 0
								} else {
									notificationItem.standard = 0
								}
							}
						});
					}
				}
      },
    },
  };
</script>
