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

import Publiclogin from './vue/components/public/auth/login.vue'
import Publicsignup from './vue/components/public/auth/signup.vue'

import Homelzcarousel from './vue/components/public/homepage/landingZoneCarousel.vue'
import Homecategories from './vue/components/public/homepage/productCategories.vue'

import Accountfunctions from './vue/components/public/accountFunctions.vue'

import Contactmain from './vue/components/public/contactMain.vue'

import Cartitems from './vue/components/public/cartItems.vue'

import Checkoutaddresses from './vue/components/public/checkout/checkoutAddresses.vue'
import Checkoutpayment from './vue/components/public/checkout/checkoutPayment.vue'

import Products from './vue/components/public/products/products.vue'
import Productpagemain from './vue/components/public/products/productPageMain.vue'


// ADMIN
import Adminheader from './vue/components/admin/site/adminHeader.vue'
import Adminfooter from './vue/components/admin/site/adminFooter.vue'
import Alerterror from './vue/components/admin/site/alertError.vue'
import Alertmessage from './vue/components/admin/site/alertMessage.vue'

import Adminlogin from './vue/components/admin/auth/login.vue'

import Admincontactmain from './vue/components/admin/contact/contactMain.vue'
import Admincontactfunctions from './vue/components/admin/contact/contactFunctions.vue'

import Userstable from './vue/components/admin/users/usersTable.vue'
import Userscreate from './vue/components/admin/users/usersCreate.vue'

import Userprofilemain from './vue/components/admin/user-profile/userProfileMain.vue'
import Userprofilefunctions from './vue/components/admin/user-profile/userProfileFunctions.vue'

import Customerstable from './vue/components/admin/customers/customersTable.vue'
import Customerscreate from './vue/components/admin/customers/customersCreate.vue'

import Customerprofilemain from './vue/components/admin/customer-profile/customerProfileMain.vue'
import Customerprofilefunctions from './vue/components/admin/customer-profile/customerProfileFunctions.vue'

import Productstable from './vue/components/admin/products/productsTable.vue'
import Productscreate from './vue/components/admin/products/productsCreate.vue'

import Productprofilemain from './vue/components/admin/product-profile/productProfileMain.vue'
import Productprofilefunctions from './vue/components/admin/product-profile/productProfileFunctions.vue'

import Lzhomecarousel from './vue/components/admin/landing-zones/home-carousel.vue'

import Categorycreate from './vue/components/admin/categories/categoryCreate.vue'
import Categorytable from './vue/components/admin/categories/categoryTable.vue'

import Categoryprofilemain from './vue/components/admin/category-profile/categoryProfileMain.vue'
import Categoryprofilefunctions from './vue/components/admin/category-profile/categoryProfileFunctions.vue'

import Variantscreate from './vue/components/admin/variants/variantsCreate.vue'
import Variantstable from './vue/components/admin/variants/variantsTable.vue'

import Variantprofilefunctions from './vue/components/admin/variant-profile/variantProfileFunctions.vue'





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


const cartItems = Vue.createApp({})
cartItems.use(VueAxios, axios)
cartItems.component('cartitems', Cartitems).mount('#cartitems')


const checkoutAddresses = Vue.createApp({})
checkoutAddresses.use(VueAxios, axios)
checkoutAddresses.component('checkoutaddresses', Checkoutaddresses).mount('#checkoutaddresses')

const checkoutPayment = Vue.createApp({})
checkoutPayment.use(VueAxios, axios)
checkoutPayment.component('checkoutpayment', Checkoutpayment).mount('#checkoutpayment')


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


const adminContactMain = createApp({})
adminContactMain.component('admincontactmain', Admincontactmain).mount('#admincontactmain')

const adminContactFunctions = createApp({})
adminContactFunctions.use(VueAxios, axios)
adminContactFunctions.component('admincontactfunctions', Admincontactfunctions).mount('#admincontactfunctions')


const usersTable = createApp({})
usersTable.component('userstable', Userstable).mount('#userstable')

const usersCreate = createApp({})
usersCreate.component('userscreate', Userscreate).mount('#userscreate')


const userProfileMain = createApp({})
userProfileMain.component('userprofilemain', Userprofilemain).mount('#userprofilemain')

const userProfileFunctions = createApp({})
userProfileFunctions.component('userprofilefunctions', Userprofilefunctions).mount('#userprofilefunctions')


const customersTable = createApp({})
customersTable.component('customerstable', Customerstable).mount('#customerstable')

const customersCreate = createApp({})
customersCreate.component('customerscreate', Customerscreate).mount('#customerscreate')


const customerProfileMain = createApp({})
customerProfileMain.component('customerprofilemain', Customerprofilemain).mount('#customerprofilemain')

const customerProfileFunctions = createApp({})
customerProfileFunctions.component('customerprofilefunctions', Customerprofilefunctions).mount('#customerprofilefunctions')


const productsTable = createApp({})
productsTable.component('productstable', Productstable).mount('#productstable')

const productsCreate = createApp({})
productsCreate.component('productscreate', Productscreate).mount('#productscreate')


const productProfileMain = createApp({})
productProfileMain.component('productprofilemain', Productprofilemain).mount('#productprofilemain')

const productProfileFunctions = createApp({})
productProfileFunctions.component('productprofilefunctions', Productprofilefunctions).mount('#productprofilefunctions')


const lzHomeCarousel = createApp({})
lzHomeCarousel.component('lzhomecarousel', Lzhomecarousel).mount('#lzhomecarousel')


const categoryCreate = createApp({})
categoryCreate.component('categorycreate', Categorycreate).mount('#categorycreate')

const categoryTable = createApp({})
categoryTable.component('categorytable', Categorytable).mount('#categorytable')


const categoryProfileMain = createApp({})
categoryProfileMain.component('categoryprofilemain', Categoryprofilemain).mount('#categoryprofilemain')

const categoryProfileFunctions = createApp({})
categoryProfileFunctions.component('categoryprofilefunctions', Categoryprofilefunctions).mount('#categoryprofilefunctions')


const variantsCreate = createApp({})
variantsCreate.component('variantscreate', Variantscreate).mount('#variantscreate')

const variantsTable = createApp({})
variantsTable.component('variantstable', Variantstable).mount('#variantstable')


const variantProfileFunctions = createApp({})
variantProfileFunctions.component('variantprofilefunctions', Variantprofilefunctions).mount('#variantprofilefunctions')
