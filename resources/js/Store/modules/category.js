import axios from "axios";

const state = {
    categories: [],
};
const actions = {
    GET_CATEGORY_LIST(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/apicategory", payload)
                .then(response => {

                    context.commit("SET_CATEGORY_LIST", response.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_CATEGORY_LIST(state, payload) {
        state.categories = payload;
    },
};
const getters = {
    categories: state => state.categories,
};

export default { state, actions, mutations, getters };
