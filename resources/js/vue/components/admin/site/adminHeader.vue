<template>
  <header class="lt">
    <nav class="desktop-nav">
      <a v-show="this.showhome == 'false'" href="/admin/dashboard" class="nav-button home-button header-button"><i class="fa-solid fa-house-chimney"></i></a>
      <i v-show="this.showhome == 'true'" class="fa-solid fa-house-chimney nav-button home-button header-button" id="non-active"></i>

			<div id="notification-header-container">
        <div @click="this.navMenuActive = (this.navMenuActive == 'notification' ? null : 'notification'), this.newNotifications = false" class="nav-button" :class="{ 'selected' : this.navMenuActive == 'notification' },{ 'new-notifications' : this.newNotifications }" id="notification-button">
          <i class="fa-solid fa-bell"></i>
					<strong v-if="this.notificationCount > 0" class="notification-count">{{ this.notificationCount }}</strong>
        </div>
      </div>

			<div id="settings-header-container">
        <div @click="this.navMenuActive = (this.navMenuActive == 'settings' ? null : 'settings')" class="nav-button" :class="{ 'selected' : this.navMenuActive == 'settings' }" id="settings-button">
          <i class="fa-solid fa-gear"></i>
        </div>
      </div>

      <div id="user-header-container">
        <div @click="this.navMenuActive = (this.navMenuActive == 'user' ? null : 'user')" class="header-button" :class="{ 'selected' : this.navMenuActive == 'user' }" id="user-button">
          <p>{{ this.sessionuser.firstName }} {{ this.sessionuser.lastName }}</p>
          <i class="fa-solid fa-user"></i>
        </div>
      </div>

      <i @click='menuActive = !menuActive' class="fa-solid fa-bars hover-background nav-button" id="menu-button"></i>
    </nav>

		<div id="notification-menu" :style="[this.navMenuActive == 'notification' ? { transform: 'translate3d(0, 100%, 0)', minWidth: this.notificationMenuWidth + 'px' } : { transform: 'translate3d(0, 0, 0)', minWidth: this.notificationMenuWidth + 'px' }]">
			<div id="notificationNav">
				<h3>Alerts</h3>
				<button class="page-button" @click="this.deleteAllNotifications()">Empty</button>
			</div>

			<div id="notificationsEmpty" class="notification" v-if="this.notificationsData.length == 0">
				<h4>No Records</h4>
			</div>

			<div class="notification" v-else v-for="(notification, i) in this.notificationsData">
				<h4><a :href="`/admin/${notification.link}`">{{ notification.group }}: {{ notification.name }}</a> <i class="fa-solid fa-square-xmark pb-danger" @click="this.deleteNotification(notification.id)"></i></h4>
				<p>{{ notification.message }}</p>
			</div>
    </div>

		<div id="settings-menu" :style="[this.navMenuActive == 'settings' ? { transform: 'translate3d(0, 100%, 0)', minWidth: this.settingsMenuWidth + 'px' } : { transform: 'translate3d(0, 0, 0)', minWidth: this.settingsMenuWidth + 'px' }]">
      <div class="settings-group" v-for="(group, groupName) in this.settingsData">
				<h4>{{ groupName }}</h4>
				<div v-for="(settings, i) in group">
					<span>{{ settings.name }}</span>

					<i v-if="settings.email" :id="'settings-' + settings.id" class="fa-solid fa-envelope active" @click="this.toggleSettings(settings.id, settings.notificationUserId, 'email')"></i>
					<i v-else :id="'settings-' + settings.id" class="fa-solid fa-envelope" @click="this.toggleSettings(settings.id, settings.notificationUserId, 'email')"></i>

					<i v-if="settings.standard" :id="'settings-' + settings.id" class="fa-solid fa-circle-check active" @click="this.toggleSettings(settings.id, settings.notificationUserId, 'standard')"></i>
					<i v-else :id="'settings-' + settings.id" class="fa-solid fa-circle-xmark" @click="this.toggleSettings(settings.id, settings.notificationUserId, 'standard')"></i>
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
    <h2 class="title">Navigation</h2>

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
			'settings',
    ],

    data() {
      return {
        menuActive: false,
        navMenuActive: false,
				userMenuWidth: 0,
				settingsMenuWidth: 0,
				settingsData: this.settings,
				notificationMenuWidth: 0,
				notificationsData: [],
				notificationCount: 0,
				newNotifications: false,
      };
    },

		mounted() {
			this.setUserMenuWidth();
			this.setNotificationMenuPosition();
			this.setSettingsMenuPosition();			
			this.reloadNotifications(true);
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

			setSettingsMenuPosition(start = true) {
				let menu = document.querySelector("#settings-menu");
				let button = document.querySelector("#settings-button");

				let buttonPosition = button.getBoundingClientRect();

				menu.style.right = (window.innerWidth - buttonPosition.left - button.offsetWidth) + "px";

				if (start) {
					setTimeout(() => {
						this.setSettingsMenuPosition(false);
					}, 500);
					
					setTimeout(() => {
						this.setSettingsMenuPosition(false);
					}, 5000);
					
					setTimeout(() => {
						this.setSettingsMenuPosition(false);
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
			async toggleSettings(id, notificationUserId, type) {
				try {
					this.response = await fetch("/header-toggleNotification/" + id + "/" + notificationUserId + "/" + type);
					this.result = await this.response.json();
					
				} catch (err) {
					console.log('----ERROR----');
					console.log(err);

				} finally {
					this.settingsData = this.result;
				}
      },

			async reloadNotifications(repeat = false) {
				try {
					this.response = await fetch("/header-reloadNotifications");
					this.result = await this.response.json();
					
				} catch (err) {
					console.log('----ERROR----');
					console.log(err);

				} finally {
					if (this.notificationCount < this.result.length) {
						this.newNotifications = true;
					}

					this.notificationCount = this.result.length;
					this.notificationsData = [];

					this.result.forEach(notification => {
						this.notificationsData.push(notification);
					});
				}

				if (repeat) {
					setTimeout(() => {
						this.reloadNotifications(true);
					}, 10000);
				}
			},

			async deleteNotification(id) {
				try {
					this.response = await fetch("/header-deleteNotification/" + id);
					this.result = await this.response.json();
					
				} catch (err) {
					console.log('----ERROR----');
					console.log(err);

				} finally {
					this.reloadNotifications();
				}
			},

			async deleteAllNotifications() {
				try {
					this.response = await fetch("/header-deleteAllNotifications");
					this.result = await this.response.json();
					
				} catch (err) {
					console.log('----ERROR----');
					console.log(err);

				} finally {
					this.reloadNotifications();
				}
			},
    },
  };
</script>
