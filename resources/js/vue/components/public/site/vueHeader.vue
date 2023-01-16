<template>
  <header class="lt">

    <nav class="desktop-nav">
      <a href="/" class="title">
        <h2 class="hover hover-underline from-left">{{ this.sitetitle }}</h2>
      </a>

      <ul id="header-nav-links">
        <li v-for="(link, i) in this.publiclinks" class="nav-link hover-background">
          <a v-if="link.title == 'icon'" :href="link.link" class="nav-link-contents icon-link">
            <i :class="link.icon"></i>
          </a>
          <div v-else-if="link.sublink" @click="(sublinksActive = !sublinksActive) && (userMenuActive = false)" class="nav-link-contents">
            {{ capFL(link.title) }} <i class="fa-solid fa-angle-down"></i>
          </div>
          <a v-else :href="link.link" class="nav-link-contents">
            {{ capFL(link.title) }}
          </a>
        </li>

        <li v-if="this.sessionuser" @click="(userMenuActive = !userMenuActive) && (sublinksActive = false)" class="nav-link user-button">
          <div class="nav-link-contents">
            {{ this.sessionuser.firstName }} {{ this.sessionuser.lastName }} <i class="fa-regular fa-user"></i>
          </div>
        </li>

        <li v-else class="nav-link user-button">
          <a href="/sign-up" class="nav-link-contents">
            Sign Up <i class="fa-regular fa-user"></i>
          </a>
        </li>
      </ul>

      <i @click='toggleMobileNav' class="fa-solid fa-bars hover-background" id="nav-menu-button"></i>
    </nav>

    <div v-if="this.sessionuser" id="user-menu" :style="[this.userMenuActive ? { transform: 'translate3d(0, 100%, 0)' } : { transform: 'translate3d(0, 0, 0)' }]">
      <a v-for="(sublink, i) in this.userlinks" :href="sublink['link']">
        <span>{{ capFL(sublink['title']) }}</span>
        <i :class="[sublink['icon']]"></i>
      </a>
    </div>

    <div v-for="(link, i) in this.publiclinks" class="nav-sublinks-container">
      <ul v-if="link.sublink" class="nav-sublinks" :style="[this.sublinksActive ? { transform: 'translate3d(0, 100%, 0)' } : { transform: 'translate3d(0, 0, 0)' }]">
        <li v-for="(sublink, i) in link.sublink" class="nav-sublink">
          <a :href="sublink.link">{{ capFL(sublink.title) }}</a>
        </li>
      </ul>
    </div>

    <div class="menu-overlay" @click="toggleOverlay"
    :class="{ 'overlay-active': mobileNav, 'overlay-non-active': !mobileNav }">
      <h2>click to close</h2>
    </div>

  </header>
</template>


<script>
  export default {
    props: [
      'sitetitle',
      'publiclinks',
      'userlinks',
      'sessionuser',
    ],

    data() {
      return {
        mobile: null,
        mobileNav: false,
        windowWidth: null,
        menuActive: false,
        mqLarge: null,
        sublinksActive: false,
        userMenuActive: false,
      }
    },

    methods: {
      toggleMobileNav() {
        this.mobileNav = !this.mobileNav;
        if (this.menuActive == false) {
          if (document.body.classList.contains("menu-non-active")){document.body.classList.remove("menu-non-active");};
          if (!(document.body.classList.contains("menu-active"))){document.body.classList.add("menu-active");};

          this.menuActive = true;
          return;
        }
        else {
          if (document.body.classList.contains("menu-active")){document.body.classList.remove("menu-active");};
          if (!(document.body.classList.contains("menu-non-active"))){document.body.classList.add("menu-non-active");};
          return;
        }
      },

      toggleOverlay() {
        this.mobileNav = false;
        this.menuActive = false;

        if (document.body.classList.contains("menu-active")){document.body.classList.remove("menu-active");};
        if (!(document.body.classList.contains("menu-non-active"))){document.body.classList.add("menu-non-active");};
        return;
      },

      capFL(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
    },
  };
</script>
