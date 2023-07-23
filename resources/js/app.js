// require('./bootstrap');
import { createApp } from 'vue'

import * as Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

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

import Homelzcarousel from './vue/components/public/homepage/landingZoneCarousel.vue'
import Homecategories from './vue/components/public/homepage/productCategories.vue'

import Accountfunctions from './vue/components/public/account/accountFunctions.vue'
import Accountorder from './vue/components/public/account/accountOrder.vue'

import Contactmain from './vue/components/public/contactMain.vue'

import Cartitems from './vue/components/public/cartItems.vue'

import Checkoutaddresses from './vue/components/public/checkout/checkoutAddresses.vue'
import Checkoutpayment from './vue/components/public/checkout/checkoutPayment.vue'
import Checkoutreview from './vue/components/public/checkout/checkoutReview.vue'
import Checkoutsuccess from './vue/components/public/checkout/checkoutSuccess.vue'

import Products from './vue/components/public/products/products.vue'
import Productpagemain from './vue/components/public/products/productPageMain.vue'


// ADMIN
import Adminheader from './vue/components/admin/site/adminHeader.vue'
import Adminfooter from './vue/components/admin/site/adminFooter.vue'
import Alerterror from './vue/components/admin/site/alertError.vue'
import Alertmessage from './vue/components/admin/site/alertMessage.vue'

import Adminlogin from './vue/components/admin/auth/login.vue'

import Admincontactfunctions from './vue/components/admin/contactFunctions.vue'

import Userscreate from './vue/components/admin/usersCreate.vue'

import Userprofilemain from './vue/components/admin/userProfileMain.vue'
import Userprofilefunctions from './vue/components/admin/userProfileFunctions.vue'

import Customerscreate from './vue/components/admin/customersCreate.vue'

import Customerprofilemain from './vue/components/admin/customerProfileMain.vue'
import Customerprofilefunctions from './vue/components/admin/customerProfileFunctions.vue'

import Productscreate from './vue/components/admin/productsCreate.vue'

import Productprofilemain from './vue/components/admin/productProfileMain.vue'
import Productprofilefunctions from './vue/components/admin/productProfileFunctions.vue'

import Lzhomecarousel from './vue/components/admin/home-carousel.vue'

import Categorycreate from './vue/components/admin/categoryCreate.vue'

import Categoryprofilefunctions from './vue/components/admin/categoryProfileFunctions.vue'

import Variantscreate from './vue/components/admin/variantsCreate.vue'

import Variantprofilefunctions from './vue/components/admin/variantProfileFunctions.vue'



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


const homeLzCarousel = createApp({})
homeLzCarousel.component('homelzcarousel', Homelzcarousel).mount('#homelzcarousel')

const homeCategories = createApp({})
homeCategories.component('homecategories', Homecategories).mount('#homecategories')


const accountFunctions = createApp({})
accountFunctions.component('accountfunctions', Accountfunctions).mount('#accountfunctions')

const accountOrder = createApp({})
accountOrder.component('accountorder', Accountorder).mount('#accountorder')


const cartItems = Vue.createApp({})
cartItems.use(VueAxios, axios)
cartItems.component('cartitems', Cartitems).mount('#cartitems')


const checkoutAddresses = Vue.createApp({})
checkoutAddresses.use(VueAxios, axios)
checkoutAddresses.component('checkoutaddresses', Checkoutaddresses).mount('#checkoutaddresses')

const checkoutPayment = Vue.createApp({})
checkoutPayment.use(VueAxios, axios)
checkoutPayment.component('checkoutpayment', Checkoutpayment).mount('#checkoutpayment')

const checkoutReview = Vue.createApp({})
checkoutReview.component('checkoutreview', Checkoutreview).mount('#checkoutreview')

const checkoutSuccess = Vue.createApp({})
checkoutSuccess.component('checkoutsuccess', Checkoutsuccess).mount('#checkoutsuccess')


const contactMain = Vue.createApp({})
const gmapKey = process.env.MIX_GOOGLE_MAPS_KEY
contactMain.use(VueGoogleMaps, {
	load: {
		key: gmapKey,
	},
}).mount('#app')
contactMain.component('contactmain', Contactmain).mount('#contactmain')


const products = createApp({})
products.component('products', Products).mount('#products')

const productPageMain = createApp({})
productPageMain.use(VueAxios, axios)
productPageMain.component('productpagemain', Productpagemain).mount('#productpagemain')


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
adminContactFunctions.use(VueAxios, axios)
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


const lzHomeCarousel = createApp({})
lzHomeCarousel.component('lzhomecarousel', Lzhomecarousel).mount('#lzhomecarousel')


const categoryCreate = createApp({})
categoryCreate.component('categorycreate', Categorycreate).mount('#categorycreate')


const categoryProfileFunctions = createApp({})
categoryProfileFunctions.component('categoryprofilefunctions', Categoryprofilefunctions).mount('#categoryprofilefunctions')


const variantsCreate = createApp({})
variantsCreate.component('variantscreate', Variantscreate).mount('#variantscreate')


const variantProfileFunctions = createApp({})
variantProfileFunctions.component('variantprofilefunctions', Variantprofilefunctions).mount('#variantprofilefunctions')
