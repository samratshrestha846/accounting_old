import axios from "axios";

const state = {
    customers: [],
};
const actions = {
    GET_CUSTOMER_LIST(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/customers", payload)
                .then(response => {

                    context.commit("SET_CUSTOMER_LIST", response.data.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    CREATE_CUSTOMER(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/customers", payload)
                .then(response => {

                    // context.commit("SET_CATEGORY_LIST", response.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_CUSTOMER_LIST(state, payload) {
        state.customers = payload;
    },
};
const getters = {
    customers: state => state.customers,
};

export default { state, actions, mutations, getters };
