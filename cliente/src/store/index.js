import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);



export default new Vuex.Store({
    state: {
        user: null,
        message: null,
    },

    mutations: {
        setUserData (state, userData) {
            state.user = userData;
            localStorage.setItem('user', JSON.stringify(userData));
            axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`
        },

        clearUserData () {
            localStorage.removeItem('user');
            location.reload()
        }
    },

    actions: {
        async login ({ commit }, credentials) {
            await axios.get('/sanctum/csrf-cookie');
            return axios
                .post('api/login', credentials)
                .then(({data}) => {
                    if(data['status']===0)
                        commit('setUserData', data['data']);
                    else
                        //console.log(data['data']);
                        this.state.message = data['data'];
                }).catch((err) => {
                    console.log(err)
                    //this.state.message = data['message'];
                })
        },

        async logout ({ commit }) {
            //await axios.post('api/logout');
            commit('clearUserData')
        }
    },

    getters : {
        isLogged: state => !!state.user
    }
})
