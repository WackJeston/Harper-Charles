<template>

  <div class="web-box contact-main">
    <div>
      <ul>
        <li><strong>Address</strong></li>
        <li v-if="this.contact['line1']">{{ this.contact['line1'] }}</li>
        <li v-if="this.contact['line2']">{{ this.contact['line2'] }}</li>
        <li v-if="this.contact['line3']">{{ this.contact['line3'] }}</li>
        <li v-if="this.contact['town']">{{ this.contact['town'] }}</li>
        <li v-if="this.contact['county']">{{ this.contact['county'] }}</li>
        <li v-if="this.contact['country']">{{ this.contact['country'] }}</li>
        <li v-if="this.contact['post code']">{{ this.contact['post code'] }}</li>
      </ul>
    </div>

    <div class="email-phone-section">
      <ul v-if="this.contact['email']">
        <li><strong>Emails</strong></li>
        <li v-for="(email, i) in this.contact['email']">{{ email.value }}</li>
      </ul>

      <ul v-if="this.contact['phone']">
        <li><strong>Phone Numbers</strong></li>
        <li v-for="(phone, i) in this.contact['phone']">{{ phone.value }}</li>
      </ul>
    </div>
  </div>

</template>


<script>
  export default {
    props: [
      'contact',
    ],
  };
</script>
