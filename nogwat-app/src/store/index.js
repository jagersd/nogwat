import { createStore } from 'vuex'
import axios from 'axios'

axios.defaults.baseURL = process.env.VUE_APP_ROOT_API
//axios.defaults.baseURL = 'http://192.168.178.25:8000/api'

export default createStore({
  state: {
    user: null,
    locale: null,
    group: null,
    searchParameters: null,
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

    setUserLocale(state, userLocale) {
      state.locale = userLocale,
      localStorage.setItem('locale', JSON.stringify(userLocale))
    },

    setGroupData(state, groupData) {
      state.group = groupData
      localStorage.setItem('group', JSON.stringify(groupData))
    },

    setSearchParameters(state, searchParameters) {
      state.searchParameters = searchParameters
      localStorage.setItem('searchParameters', JSON.stringify(searchParameters))
    },

    clearSeachParameters () {
      localStorage.setItem('searchParameters', null)
    },
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