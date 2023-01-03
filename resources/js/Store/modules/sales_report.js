import axios from "axios";

const state = {
};
const actions = {
    GET_TODAY_SALES_REPORT(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get('/api/outlets/'+payload.outlet_id+'/salesreports/today-sale', payload)
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
