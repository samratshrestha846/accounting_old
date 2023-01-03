import axios from "axios";

const state = {
    restaurantDeliveryPartnerList: [],
};
const actions = {
    GET_RESTAURANT_DELIVERY_PARTNER_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios.get("/api/hotel/order/delivery_partners", payload)
                .then(response => {
                    context.commit('SET_RESTAURANT_DELIVERY_PARTNER_ITEM', response.data.data);
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_RESTAURANT_DELIVERY_PARTNER_ITEM(state, payload) {
        state.restaurantDeliveryPartnerList = payload;
    },
};
const getters = {
    restaurantDeliveryPartnerList: state => state.restaurantDeliveryPartnerList,
};

export default { state, actions, mutations, getters };
