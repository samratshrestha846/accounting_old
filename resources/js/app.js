require('./bootstrap');

import Vue from "vue";
import { createInertiaApp } from '@inertiajs/inertia-vue'
import store from "./Store/index.js"

//sweetalert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

//vuelidate
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate);

//vue toast notification
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
Vue.use(VueToast)

Vue.component('session-login', () => import("./components/SessionLogin"));
Vue.component('pos-sale', require('./Pages/Pos.vue').default);
Vue.component('pos-todaysalereport-modal', require('./Pages/Pos/TodaySaleReportModal').default);
Vue.component('userdropdownoutetlist', () => import("./Pages/Pos/UserDropdownOutletList"));
Vue.component('pos-billing-invoice', () => import("./Pages/Billing/PosBillingInvoice"));

Vue.component('hotel-pos-order', require('./Pages/Hotel/pos/AppLayout.vue').default);
Vue.component('make-payment-modal', require('./Pages/Hotel/order_item/CreatePaymentModal').default);

//print
Vue.component('print-kot-billing', require('./Pages/Hotel/print/KotBilling.vue').default);
Vue.component('print-order-item-invoice', require('./Pages/Hotel/print/OrderItemInvoice.vue').default);


Vue.mixin(import("./mixins/alert.js"));

import vuetify from "./plugins/vuetify.js"

const app = new Vue({
    el: '#app',
    store,
    vuetify
});

import filter from './services/filters'

Vue.prototype.$filters = filter

