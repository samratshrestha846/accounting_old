import axios from "axios";

const state = {
    floors: [],
};
const actions = {
    GET_RESTAURANT_FLOOR_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/floors", payload)
                .then(response => {
                    context.commit('SET_RESTAURANT_FLOOR_ITEM', response.data.data);
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_RESTAURANT_FLOOR_ITEM(state, payload) {
        state.floors = payload;
    },
};
const getters = {
    floors: state => state.floors,
};

export default { state, actions, mutations, getters };
