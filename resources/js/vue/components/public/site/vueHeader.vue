<template>
  <header class="dk">

    <nav class="desktop-nav">
      <a href="/" class="title">
        <h2 id="header-title">{{ this.sitetitle }}</h2>
        <h2 id="header-title-mini">{{ this.sitetitlemini }}</h2>
      </a>

      <ul id="header-nav-links">
        <li class="nav-link hover-background">
          <a href="/cart" class="nav-link-contents icon-link">
            <i class="fa-solid fa-cart-shopping"></i>
          </a>
        </li>

        <li @click="(userMenuActive = !userMenuActive) && (sublinksActive = false)" class="nav-link hover-background user-button">
          <div v-if="this.sessionuser" class="nav-link-contents">
            {{ this.sessionuser.firstName }} {{ this.sessionuser.lastName }} <i class="fa-solid fa-user"></i>
          </div>

          <div v-else class="nav-link-contents">
            <i class="fa-regular fa-user icon-link"></i>
          </div>
        </li>
      </ul>

      <i @click='toggleMobileNav' class="fa-solid fa-bars hover-background" id="nav-menu-button"></i>
    </nav>

    <div id="user-menu-container">
      <div v-if="this.sessionuser" id="user-menu" :style="[this.userMenuActive ? { transform: 'translate3d(0, 0, 0)' } : { transform: 'translate3d(0, -100%, 0)' }]">
        <a v-for="(sublink, i) in this.userlinks" :href="sublink['link']">
          <span>{{ capFL(sublink['title']) }}</span>
          <i :class="[sublink['icon']]"></i>
        </a>
      </div>

      <div v-else id="user-menu" :style="[this.userMenuActive ? { transform: 'translate3d(0, 0, 0)' } : { transform: 'translate3d(0, -100%, 0)' }]">
        <a href="/login">
          <span>Login</span>
          <i class="fa-solid fa-user-check"></i>
        </a>
        <a href="/sign-up">
          <span>Sign Up</span>
          <i class="fa-solid fa-user-plus"></i>
        </a>
      </div>
    </div>

    <div class="menu-overlay" @click="toggleOverlay"></div>

  </header>
</template>


<script>
  export default {
    props: [
      'sitetitle',
      'sitetitlemini',
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
        let menu = document.querySelector(".mobile-nav");
        let overlay = document.querySelector(".menu-overlay");

        if (this.menuActive == false) {
          menu.classList.remove("menu-non-active");
          menu.classList.add("menu-active");

          overlay.style.display = "block";
          overlay.style.opacity = 0.6;

          this.menuActive = true;
        }
        else {
          menu.classList.remove("menu-active");
          menu.classList.add("menu-non-active");

          overlay.style.display = "none";
          overlay.style.opacity = 0;
        };

        
      },

      toggleOverlay() {
        this.mobileNav = false;
        this.menuActive = false;
        let menu = document.querySelector(".mobile-nav");
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
