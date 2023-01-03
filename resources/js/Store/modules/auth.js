import axios from "axios";

const state = {
    cartItems: [],
};
const actions = {
    LOGOUT(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/logout", payload)
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
