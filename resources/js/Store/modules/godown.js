import axios from "axios";

const state = {
    godowns: [],
};
const actions = {
    GET_GODOWN_LIST(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/godowns", payload)
                .then(response => {

                    context.commit("SET_GODOWN_LIST", response.data.data);

                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_GODOWN_LIST(state, payload) {
        state.godowns = payload;
    },
};
const getters = {
    godowns: state => state.godowns,
};

export default { state, actions, mutations, getters };
