import axios from "axios";

const state = {
    rooms: [],
};
const actions = {
    GET_RESTAURANT_ROOM_ITEM(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/rooms", payload)
                .then(response => {
                    context.commit('SET_RESTAURANT_ROOM_ITEM', response.data.data);
                    resolve(response);
                })
                .catch(errors => reject(errors));
        });
    },

}
const mutations = {
    SET_RESTAURANT_ROOM_ITEM(state, payload) {
        state.rooms = payload;
    },
};
const getters = {
    rooms: state => state.rooms,
};

export default { state, actions, mutations, getters };
