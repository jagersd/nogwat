import { createStore } from 'vuex'
import axios from 'axios'

axios.defaults.baseURL = 'http://127.0.0.1:8000/api'

export default createStore({
  state: {
    user: null,
    groupId: null,
  },

  mutations: {
    setUserData (state, userData) {
      state.user = userData
      localStorage.setItem('user', JSON.stringify(userData))
      axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`
    },

    clearUserData () {
      localStorage.removeItem('user')
      location.reload()
    },

    setDefaultGroup(state, defaultGroup) {
      state.groupId = defaultGroup
      localStorage.setItem('groupId', defaultGroup)
    }
  },

  actions: {
    login ({ commit }, credentials) {
      return axios
        .post('/login', credentials)
        .then(({ data }) => {
          commit('setUserData', data)
        })
    },

    logout ({ commit }) {
      commit('clearUserData')
    },
  },

  getters : {
    isLogged: state => !!state.user
  }
})