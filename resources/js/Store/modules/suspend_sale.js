import axios from "axios";

const state = {
    suspendedSaleItems: [],
};
const actions = {
    GET_SUSPENDED_PRODUCT_LIST(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/products/suspendedsales/"+payload.suspended_bill_id+"/suspendeditems", payload)
                .then(response => {

                    context.commit("SET_SUSPENDED_SALE_PRODUCT_LIST", response.data.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    SUSPEND_SALE(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/products/sales/suspends", payload)
                .then(response => {

                    // context.commit("SET_CATEGORY_LIST", response.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    UPDATE_SUSPEND_SALE(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .patch("/api/products/suspendedsales/" + payload.suspended_bill_id, payload)
                .then(response => {

                    // context.commit("SET_CATEGORY_LIST", response.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    DELETE_SUSPEND_PRODUCT_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .delete("/api/products/suspendeditems/" + payload.suspended_bill_id, payload)
                .then(response => {

                    // context.commit("SET_CATEGORY_LIST", response.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },
    CANCLE_SUSPEND_PRODUCT_SALE(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/products/suspendedsales/" + payload.suspended_bill_id + '/cancle', payload)
                .then(response => {

                    // context.commit("SET_CATEGORY_LIST", response.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    }

}
const mutations = {
    SET_SUSPENDED_SALE_PRODUCT_LIST(state, payload) {
        state.suspendedSaleItems = payload;
    },
};
const getters = {
    suspendedSaleItems: state => state.suspendedSaleItems,
};

export default { state, actions, mutations, getters };
