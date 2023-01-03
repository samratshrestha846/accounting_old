
import Vue from "vue";
import store from "./Store/index.js"

//sweetalert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

//vue toast notification
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
Vue.use(VueToast)


Vue.component('make-payment-modal', require('./Pages/Hotel/order_item/CreatePaymentModal').default);

const app = new Vue({
    el: '#main',
    store,
    data(){
        return {
            timeout: null,
            sessionTime: window.data.session_time,
        }
    },
    mounted() {
        // Reset the timer
        // document.addEventListener('mousemove',  );
        // document.addEventListener('onclick', this.resetTimerSession);
        this.resetTimerSession();
    },
    unmounted() {
     // document.removeEventListener('mousemove', this.resetTimerSession)
    //   document.removeEventListener('onclick', this.resetTimerSession)
    },
    methods: {
        resetTimerSession() {
            let self = this;
            // console.log("Session activity loading");
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                // console.log("i am logout");
                // this.session = true;
                this.showAlertMessage();
            }, parseInt(this.sessionTime) * 60 * 1000);
        },
        showAlertMessage(){
            this.$swal.fire({
                title: "Your session has expired. Please reload the page.",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: `Sure`,
                denyButtonText: `Cancle`,
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                    return;
                } else{
                    this.showAlertMessage();
                }
            });
        },
    }
});

import filter from './services/filters'

Vue.prototype.$filters = filter
