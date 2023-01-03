import axios from "axios";

const state = {
    restaurantFoodList: [],
};
const actions = {
    GET_RESTAURANT_FOOD_LIST(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/hotel/order/fooditems", payload)
                .then(response => {

                    context.commit("SET_RESTAURANT_FOOD_LIST", response.data.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_RESTAURANT_FOOD_LIST(state, payload) {
        state.restaurantFoodList = payload;
    },
};
const getters = {
    restaurantFoodList: state => state.restaurantFoodList,
};

export default { state, actions, mutations, getters };
