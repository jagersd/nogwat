<template>
  <ion-app>
    <ion-router-outlet></ion-router-outlet>
  </ion-app>
</template>

<script>
import { IonApp, IonRouterOutlet } from '@ionic/vue';
import { defineComponent } from 'vue';
import { mapGetters } from 'vuex'
import axios from 'axios'

export default defineComponent({
  name: 'App',
  components: {
    IonApp,
    IonRouterOutlet
  },
  computed: {
    ...mapGetters([
      'isLogged'
    ])
  },
  mounted() {
    const userInfo = localStorage.getItem('user')
    if (userInfo) {
      const userData = JSON.parse(userInfo)
      this.$store.commit('setUserData', userData)
    }
    axios.interceptors.response.use(
      response => response,
      error => {
        if (!error.response){
          this.$router.push({name: 'Error'})
        }
        switch (error.response.status){
          case 401: this.$store.dispatch('logout')
          break
          case 404: this.$router.push({name: 'Home'})
          break
        }
        return Promise.reject(error)
      }
    )
  },
  methods: {
    logout () {
      this.$store.dispatch('logout')
      window.localStorage.clear()
      .then(this.$router.push({ name: 'Login'}))
    }
  }

});
</script>
