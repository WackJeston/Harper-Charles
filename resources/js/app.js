// require('./bootstrap');
import { createApp } from 'vue'

import * as Vue from 'vue'

import VueGoogleMaps from '@fawmi/vue-google-maps'


// PUBLIC
import Vueheader from './vue/components/public/site/vueHeader.vue'
import Vuemenu from './vue/components/public/site/vueMenu.vue'
import Vuefooter from './vue/components/public/site/vueFooter.vue'

import Publicerror from './vue/components/public/site/publicError.vue'
import Publicmessage from './vue/components/public/site/publicMessage.vue'
import Publicalert from './vue/components/public/site/publicAlert.vue'

import Publiclogin from './vue/components/public/auth/login.vue'
import Publicsignup from './vue/components/public/auth/signup.vue'

import Accountfunctions from './vue/components/public/account/accountFunctions.vue'
import Accountorder from './vue/components/public/account/accountOrder.vue'

import Bannerhometop from './vue/components/public/homepage/bannerHomeTop.vue'
import Homecategories from './vue/components/public/homepage/productCategories.vue'

import Contactmain from './vue/components/public/contactMain.vue'
import Googlemaps from './vue/components/public/googleMaps.vue'

import Categorybanner from './vue/components/public/products/categoryBanner.vue'

import Productpagemain from './vue/components/public/products/productPageMain.vue'
import Productpageinfo from './vue/components/public/products/productPageInfo.vue'

import Basketlines from './vue/components/public/basketLines.vue'

import Checkoutheader from './vue/components/public/checkout/checkoutHeader.vue'
import Checkoutaddresses from './vue/components/public/checkout/checkoutAddresses.vue'
import Checkoutpayment from './vue/components/public/checkout/checkoutPayment.vue'
import Checkoutreview from './vue/components/public/checkout/checkoutReview.vue'
import Checkoutsuccess from './vue/components/public/checkout/checkoutSuccess.vue'



// ADMIN
import Adminheader from './vue/components/admin/site/adminHeader.vue'
import Adminfooter from './vue/components/admin/site/adminFooter.vue'
import Alerterror from './vue/components/admin/site/alertError.vue'
import Alertmessage from './vue/components/admin/site/alertMessage.vue'

import Adminlogin from './vue/components/admin/auth/login.vue'

import Admincontactfunctions from './vue/components/admin/contactFunctions.vue'

import Userscreate from './vue/components/admin/usersCreate.vue'

import Userprofilefunctions from './vue/components/admin/userProfileFunctions.vue'

import Customerscreate from './vue/components/admin/customersCreate.vue'

import Customerprofilefunctions from './vue/components/admin/customerProfileFunctions.vue'

import Productscreate from './vue/components/admin/productsCreate.vue'

import Productprofilefunctions from './vue/components/admin/productProfileFunctions.vue'

import Orderprofilefunctions from './vue/components/admin/orderProfileFunctions.vue'

import Categorycreate from './vue/components/admin/categoryCreate.vue'

import Categoryprofilefunctions from './vue/components/admin/categoryProfileFunctions.vue'

import Variantscreate from './vue/components/admin/variantsCreate.vue'

import Variantprofilefunctions from './vue/components/admin/variantProfileFunctions.vue'

import Bannerprofilefunctions from './vue/components/admin/bannerProfileFunctions.vue'



// PUBLIC
const vueHeader = createApp({})
vueHeader.component('vueheader', Vueheader).mount('#vueheader')

const vueMenu = createApp({})
vueMenu.component('vuemenu', Vuemenu).mount('#vuemenu')

const vueFooter = createApp({})
vueFooter.component('vuefooter', Vuefooter).mount('#vuefooter')


const publicError = createApp({})
publicError.component('publicerror', Publicerror).mount('#publicerror')

const publicMessage = createApp({})
publicMessage.component('publicmessage', Publicmessage).mount('#publicmessage')

const publicAlert = createApp({})
publicAlert.component('publicalert', Publicalert).mount('#publicalert')


const publicLogin = createApp({})
publicLogin.component('publiclogin', Publiclogin).mount('#publiclogin')

const publicSignup = createApp({})
publicSignup.component('publicsignup', Publicsignup).mount('#publicsignup')


const accountFunctions = createApp({})
accountFunctions.component('accountfunctions', Accountfunctions).mount('#accountfunctions')

const accountOrder = createApp({})
accountOrder.component('accountorder', Accountorder).mount('#accountorder')


const bannerHomeTop = createApp({})
bannerHomeTop.component('bannerhometop', Bannerhometop).mount('#bannerhometop')

const homeCategories = createApp({})
homeCategories.component('homecategories', Homecategories).mount('#homecategories')


const contactMain = Vue.createApp({})
contactMain.component('contactmain', Contactmain).mount('#contactmain')

const googleMaps = Vue.createApp({})
const gmapKey = process.env.MIX_GOOGLE_MAPS_KEY
googleMaps.use(VueGoogleMaps, {
	load: {
		key: gmapKey,
	},
}).mount('#app')
googleMaps.component('googlemaps', Googlemaps).mount('#googlemaps')


const categoryBanner = createApp({})
categoryBanner.component('categorybanner', Categorybanner).mount('#categorybanner')


const productPageMain = createApp({})
productPageMain.component('productpagemain', Productpagemain).mount('#productpagemain')


const productPageInfo = createApp({})
productPageInfo.component('productpageinfo', Productpageinfo).mount('#productpageinfo')


const basketLines = Vue.createApp({})
basketLines.component('basketlines', Basketlines).mount('#basketlines')


const checkoutHeader = Vue.createApp({})
checkoutHeader.component('checkoutheader', Checkoutheader).mount('#checkoutheader')

const checkoutAddresses = Vue.createApp({})
checkoutAddresses.component('checkoutaddresses', Checkoutaddresses).mount('#checkoutaddresses')

const checkoutPayment = Vue.createApp({})
checkoutPayment.component('checkoutpayment', Checkoutpayment).mount('#checkoutpayment')

const checkoutReview = Vue.createApp({})
checkoutReview.component('checkoutreview', Checkoutreview).mount('#checkoutreview')

const checkoutSuccess = Vue.createApp({})
checkoutSuccess.component('checkoutsuccess', Checkoutsuccess).mount('#checkoutsuccess')


// ADMIN
const adminHeader = createApp({})
adminHeader.component('adminheader', Adminheader).mount('#adminheader')

const adminFooter = createApp({})
adminFooter.component('adminfooter', Adminfooter).mount('#adminfooter')

const alertError = createApp({})
alertError.component('alerterror', Alerterror).mount('#alerterror')

const alertMessage = createApp({})
alertMessage.component('alertmessage', Alertmessage).mount('#alertmessage')


const adminLogin = createApp({})
adminLogin.component('adminlogin', Adminlogin).mount('#adminlogin')

// const adminConfirmEmail = createApp({})
// adminConfirmEmail.component('adminconfirmemail', Adminconfirmemail).mount('#adminconfirmemail')


const adminContactFunctions = createApp({})
adminContactFunctions.component('admincontactfunctions', Admincontactfunctions).mount('#admincontactfunctions')


const usersCreate = createApp({})
usersCreate.component('userscreate', Userscreate).mount('#userscreate')


const userProfileFunctions = createApp({})
userProfileFunctions.component('userprofilefunctions', Userprofilefunctions).mount('#userprofilefunctions')


const customersCreate = createApp({})
customersCreate.component('customerscreate', Customerscreate).mount('#customerscreate')


const customerProfileFunctions = createApp({})
customerProfileFunctions.component('customerprofilefunctions', Customerprofilefunctions).mount('#customerprofilefunctions')


const productsCreate = createApp({})
productsCreate.component('productscreate', Productscreate).mount('#productscreate')


const productProfileFunctions = createApp({})
productProfileFunctions.component('productprofilefunctions', Productprofilefunctions).mount('#productprofilefunctions')


const orderProfileFunctions = createApp({})
orderProfileFunctions.component('orderprofilefunctions', Orderprofilefunctions).mount('#orderprofilefunctions')


const categoryCreate = createApp({})
categoryCreate.component('categorycreate', Categorycreate).mount('#categorycreate')


const categoryProfileFunctions = createApp({})
categoryProfileFunctions.component('categoryprofilefunctions', Categoryprofilefunctions).mount('#categoryprofilefunctions')


const variantsCreate = createApp({})
variantsCreate.component('variantscreate', Variantscreate).mount('#variantscreate')


const variantProfileFunctions = createApp({})
variantProfileFunctions.component('variantprofilefunctions', Variantprofilefunctions).mount('#variantprofilefunctions')


const bannerProfileFunctions = createApp({})
bannerProfileFunctions.component('bannerprofilefunctions', Bannerprofilefunctions).mount('#bannerprofilefunctions')
