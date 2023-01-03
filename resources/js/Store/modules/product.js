import axios from "axios";

const state = {
    products: [],
};
const actions = {
    GET_POS_PRODUCT_LIST_BY_OUTLET_ID(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get('/api/pos/outlets/'+payload.outlet_id+'/products', payload)
                .then(response => {

                    context.commit("SET_PRODUCT_LIST", response.data.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    FIND_POS_PRODUCT_BY_OUTLET_ID_AND_PRODUCT_CODE(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get('/api/pos/outlets/'+payload.outlet_id+'/products/' + payload.product_code,payload)
                .then(response => {

                    context.commit("SET_PRODUCT_LIST", response.data.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    }

}
const mutations = {
    SET_PRODUCT_LIST(state, payload) {
        state.products = payload;
    },
};
const getters = {
    products: state => state.products,

};
export default { state, actions, mutations, getters };
