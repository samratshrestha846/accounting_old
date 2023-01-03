import axios from "axios";
import {status} from "../../enums/OrderItemStatus"

const state = {
    restaurantFoodList: [],
};
const actions = {
    CREATE_RESTAURANT_ORDER_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/hotel/order/fooditems/place", payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    GET_RESTAURANT_ORDER_DETAIL(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/hotel/order/get_order_detail", payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    GET_RESTAURANT_ORDER_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/hotel/order/orders", payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    FIND_RESTAURANT_ORDER_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/hotel/order/orders/" + payload.order_id, payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    CANCEL_RESTAURANT_ORDER(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/hotel/order/orders/" + payload.order_id + '/cancle', payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    SUSPEND_RESTAURANT_ORDER(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/hotel/order/orders/" + payload.order_id + '/suspend', payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    RESTORE_RESTAURANT_ORDER(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/hotel/order/orders/" + payload.order_id + '/restore', payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    }

}
const mutations = {
    // SET_RESTAURANT_FOOD_LIST(state, payload) {
    //     state.restaurantFoodList = payload;
    // },
};
const getters = {
    // restaurantFoodList: state => state.restaurantFoodList,
};

export default { state, actions, mutations, getters };
