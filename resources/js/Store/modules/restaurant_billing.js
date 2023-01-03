

import axios from "axios";

const state = {

};
const actions = {
    FIND_RESTAURANT_BILLING(context, billing_id) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/hotel/order/billings/" + billing_id)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {

};
const getters = {
};

export default { state, actions, mutations, getters };
