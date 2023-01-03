import axios from "axios";

const state = {
    restaurantTableList: [],
};
const actions = {
    GET_RESTAURANT_TABLE_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/tables", payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    GET_RESTAURANT_ORDER_TABLE_LIST(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/hotel/order/tables", payload)
                .then(response => {

                    context.commit("SET_RESTAURANT_TABLE_LIST", response.data.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_RESTAURANT_TABLE_LIST(state, payload) {
        state.restaurantTableList = payload;
    },
};
const getters = {
    restaurantTableList: state => state.restaurantTableList,
};

export default { state, actions, mutations, getters };
