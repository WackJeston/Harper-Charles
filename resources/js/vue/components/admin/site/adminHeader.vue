<template>
  <header class="lt">
    <nav class="desktop-nav">
      <!-- <a href="/admin/dashboard" class="title"><h2 class="hover">{{ this.sitetitle }}</h2></a> -->
      <a v-show="this.showhome == 'false'" href="/admin/dashboard" class="nav-button home-button"><i class="fa-solid fa-house-chimney"></i></a>
      <i v-show="this.showhome == 'true'" class="fa-solid fa-house-chimney nav-button home-button" id="non-active"></i>

      <div id="user-header-container">
        <div @click="userMenuActive = !userMenuActive" class="header-button" id="user-button">
          <p>{{ this.sessionuser.firstName }} {{ this.sessionuser.lastName }}</p>
          <i class="fa-solid fa-user"></i>
        </div>
      </div>

      <i @click='menuActive = !menuActive' class="fa-solid fa-bars hover-background nav-button" id="menu-button"></i>
    </nav>

    <div id="user-menu" :style="[this.userMenuActive ? { transform: 'translate3d(0, 100%, 0)' } : { transform: 'translate3d(0, 0, 0)' }]">
      <a :href="'/admin/user-profile/' + this.sessionuser.id">My Profile</a>
      <a href="/adminLogout">Log Out</a>
    </div>

    <div class="menu-overlay" @click="menuActive = false" v-show="menuActive"></div>
  </header>

  <nav class="admin-menu lt"
  :class="{ 'menu-active': menuActive, 'menu-non-active': !menuActive }">
    <h2 class="title">Backend Menu</h2>

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
    ],

    data() {
      return {
        menuActive: false,
        userMenuActive: false,
      };
    },

    methods: {
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
    },
  };
</script>
