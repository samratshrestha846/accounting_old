import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import auth from './modules/auth.js'
import product from './modules/product.js'
import category from './modules/category'
import customer from './modules/customer'
import suspend_sale from './modules/suspend_sale'
import cart_item from './modules/cart_item.js'
import godown from './modules/godown.js'
import sales_report from './modules/sales_report.js'
import restaurant_food from './modules/restaurant_food.js'
import restaurant_table from './modules/restaurant_table.js'
import restaurant_order from './modules/restaurant_order.js'
import restaurant_billing from './modules/restaurant_billing.js'
import restaurant_floor from './modules/restaurant_floor.js'
import restaurant_room from './modules/restaurant_room.js'
import restaurant_delivery_partner from './modules/restaurant_delivery_partners.js'

export default new Vuex.Store({
    state: {
        districts: [],
        provinces: [],
    },
    mutations: {
        SET_DISTRICT_LIST(state, payload) {
            state.districts = payload;
        },
        SET_PROVINCE_LIST(state, payload) {
            state.provinces = payload;
        },
    },
    actions: {
        GET_DISTRICT_LIST(context, payload) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/districts", payload)
                    .then(response => {

                        context.commit("SET_DISTRICT_LIST", response.data.data);

                        resolve(response);
                    })
                    .catch(errors => reject(errors));
            });
        },
        GET_PROVINCE_LIST(context, payload) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/provinces", payload)
                    .then(response => {

                        context.commit("SET_PROVINCE_LIST", response.data.data);

                        resolve(response);
                    })
                    .catch(errors => reject(errors));
            });
        },
    },
    getters: {
        districts: state => state.districts,
        provinces: state => state.provinces,
    },
    modules: {
        auth,
        product,
        category,
        customer,
        suspend_sale,
        cart_item,
        godown,
        sales_report,
        restaurant_food,
        restaurant_table,
        restaurant_order,
        restaurant_billing,
        restaurant_floor,
        restaurant_room,
        restaurant_delivery_partner,
    }
})
