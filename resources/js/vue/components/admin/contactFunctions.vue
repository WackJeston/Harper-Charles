<template>
  <!-- Buttons -->
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'edit' }"
  @click="show == 'edit' ? show = false : show = 'edit'">Edit Address</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'email' }"
  @click="show == 'email' ? show = false : show = 'email'">Manage Emails</button>
  <button class="page-button" type="button" :class="{ 'button-active' : show == 'phone' }"
  @click="show == 'phone' ? show = false : show = 'phone'">Manage Phones</button>

  <!-- Edit -->
  <form class="web-box dk" v-show="show == 'edit'" id="updateForm" :action="'/contactUpdateAddress/' + this.lat + '/' + this.lng" method="post" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = false"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="line1">First Line</label>
    <input v-if="this.contact['line1']" :value="this.contact['line1']" v-once type="text" name="line1" id="line1" maxlength="200">
    <input v-else type="text" name="line1" id="line1" maxlength="200">

    <label for="line2">Second Line</label>
    <input v-if="this.contact['line2']" :value="this.contact['line2']" v-once type="text" name="line2" id="line2" maxlength="200">
    <input v-else type="text" name="line2" id="line2" maxlength="200">

    <label for="line3">Third Line</label>
    <input v-if="this.contact['line3']" :value="this.contact['line3']" v-once type="text" name="line3" id="line3" maxlength="200">
    <input v-else type="text" name="line3" id="line3" maxlength="200">

    <label for="city">Town/City</label>
    <input v-if="this.contact['city']" :value="this.contact['city']" v-once type="text" name="city" id="city" maxlength="200">
    <input v-else type="text" name="city" id="city" maxlength="200">

    <label for="region">Region</label>
    <input v-if="this.contact['region']" :value="this.contact['region']" v-once type="text" name="region" maxlength="200">
    <input v-else type="text" name="region" maxlength="200">

    <label for="country">Country</label>
    <input v-if="this.contact['country']" :value="this.contact['country']" v-once type="text" name="country" maxlength="200">
    <input v-else type="text" name="country" maxlength="200">

    <label for="postcode">Post Code</label>
    <input v-if="this.contact['postcode']" :value="this.contact['postcode']" v-once type="text" name="postcode" id="postcode" maxlength="200">
    <input v-else type="text" name="postcode" id="postcode" maxlength="200">

    <button class="submit" type="button" @click="update()">Update</button>
  </form>

  <!-- Emails Form -->
  <form class="web-box dk" v-show="show == 'email'" action="/contactCreateEmail" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = null"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="email">Email<span> *</span></label>
    <input type="email" name="email" maxlength="200" required>

    <button class="submit" type="submit">Add</button>
  </form>

  <!-- Emails table -->
  <div v-html="this.emailstable.html" v-show="show == 'email'"></div>

  <!-- Phones Form -->
  <form class="web-box dk" v-show="show == 'phone'" action="/contactCreatePhone" method="POST" enctype="multipart/form-data">
    <i class="fa-solid fa-xmark" @click="show = null"></i>
    <input type="hidden" name="_token" :value="csrf">

    <label for="phone">Number<span> *</span></label>
    <input type="tel" name="phone" maxlength="20" required>

    <button class="submit" type="submit">Add</button>
  </form>

  <!-- Phones table -->
  <table class="web-box" v-show="show == 'phone'">
    <tr>
      <th id="phoneColumn1">#</th>
      <th id="phoneColumn2">Number</th>
      <th id="phoneColumn3"></th>
    </tr>

    <tr v-for="(phone, i) in this.contact['phone']">
      <td id="phoneColumn1"><div>{{ phone.id }}</div></td>
      <td id="phoneColumn2"><div>{{ phone.value }}</div></td>
      <td id="phoneColumn3" class="tr-buttons">
        <a :href="'/contactDeletePhone/' + phone.id">
          <i class="fa-solid fa-trash-can">
            <div class="button-label">
              <p>Delete Number</p>
              <div></div>
            </div>
          </i>
        </a>
      </td>
    </tr>

    <div v-if="!this.contact['phone']" class="empty-table">
      <h3>No Numbers</h3>
    </div>
  </table>
</template>


<script>
  export default {
    props: [
      'contact',
			'emailstable',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        show: false,
        showPassword: false,
        lat: 0,
        lng: 0,
      };
    },

    methods: {
      async update() {
        try {
          let query = document.getElementById('line1').value.replace(',', '');
          query += ' ' + document.getElementById('line2').value.replace(',', '');
          query += ' ' + document.getElementById('line3').value.replace(',', '');
          query += ' ' + document.getElementById('city').value.replace(',', '');
          query += ' ' + document.getElementById('postcode').value.replace(',', '');

          query = encodeURI(query);

          this.geoData = await this.$http.post(
            'https://maps.googleapis.com/maps/api/geocode/json?address=' + query + '&key=' + process.env.MIX_GOOGLE_MAPS_KEY
          );

        } catch (err) {
          console.log(err);
        } finally {
          this.lat = this.geoData.data.results[0].geometry.location.lat;
          this.lng = this.geoData.data.results[0].geometry.location.lng;

          setTimeout(() => {
            document.getElementById('updateForm').submit();
          }, 10);
        }
      },
    },
  };
</script>
