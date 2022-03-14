import { createStore } from 'vuex'
import axios from 'axios'

axios.defaults.baseURL = process.env.VUE_APP_ROOT_API

export default createStore({
  state: {
    user: null,
    locale: localStorage.getItem("locale") || 'nl',
    group: null,
    searchParameters: null,
    appStatus: null
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

    setAppstatus(state, readyCheck){
      state.appStatus = readyCheck
      localStorage.setItem('appStatus', JSON.stringify(readyCheck))
    },

    setUserLocale(state, userLocale) {
      state.locale = userLocale,
      localStorage.setItem('locale', userLocale)
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
    isLogged: state => !!state.user,
    getAppLanguage: (state) => state.locale
  }
})