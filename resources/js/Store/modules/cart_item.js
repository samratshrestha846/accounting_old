import axios from "axios";

const state = {
    cartItems: [],
};
const actions = {
    CREATE_SALE_CART_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/products/cartitems", payload)
                .then(response => {
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_CART_ITEM_LIST(state, payload) {
        state.cartItems = payload;
    },
};
const getters = {
    cartItems: state => state.cartItems,
};

export default { state, actions, mutations, getters };
