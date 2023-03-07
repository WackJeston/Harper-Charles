<template>
  <nav class="mobile-nav dk">
    <a href="/" class="title">
      <h2>{{ this.sitetitle }}</h2>
    </a>

    <ul>
      <div v-if="this.sessionuser" class="nav-link">
        <a class="primary-nav-link">
          <i class="fa-regular fa-user nav-link-icon"></i>
          <p>{{ this.sessionuser.firstName + ' ' + this.sessionuser.lastName }}</p>
          <i @click="toggleLinks(999, false, 1)" class="fa-solid fa-angle-down hover-background" :class="'ladown' + 999"></i>
          <i @click="toggleLinks(999, true, 1)" class="fa-solid fa-angle-up hover-background" :class="'laup' + 999"></i>
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
            <p>Login</p>
          </a>
        </div>

      <div v-for="(link, i) in this.publiclinks" class="nav-link">
        <a v-if="!link['sublink']" class="primary-nav-link" :href="link['link']">
          <i :class="[link['icon']]" class="nav-link-icon"></i>
          <li>{{ capFL(link['title']) }}</li>
        </a>

        <a v-if="link['sublink']" class="primary-nav-link">
          <i :class="[link['icon']]" class="nav-link-icon"></i>
          <li>{{ capFL(link['title']) }}</li>
          <i @click="toggleLinks(i, false)" v-show="link['sublink']" class="fa-solid fa-angle-down" :class="'ladown' + [i]"></i>
          <i @click="toggleLinks(i, true)" v-show="link['sublink']" class="fa-solid fa-angle-up" :class="'laup' + [i]"></i>
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
      toggleLinks(i, open, usermenu = 0) {
        if(open == false) {
          let list = document.querySelector(".sublist" + i);
          let laDown = document.querySelector(".ladown" + i);
          let laUp = document.querySelector(".laup" + i);
          let height = 0;
          if (usermenu == 0) {
            height = 40 * this.publiclinks[i]['sublink'].length;
          } else {
            height = 40 * list.childElementCount;
          }
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
