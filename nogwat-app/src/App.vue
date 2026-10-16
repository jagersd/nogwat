<template>
  <ion-app>
    <ion-tabs>
      <ion-router-outlet></ion-router-outlet>
      <ion-tab-bar v-if="isLogged" slot="bottom">
        <ion-tab-button tab="groups" href="/groups">
          <ion-icon :icon="heart" size="large"></ion-icon>
          <ion-label>{{ $t('masterLayout.groups') }}</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="lists" href="/lists">
          <ion-icon :icon="list" size="large"></ion-icon>
          <ion-label>{{ $t('masterLayout.shoppingList') }}</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="recipes" href="/recipes">
          <ion-icon :icon="restaurant" size="large"></ion-icon>
          <ion-label>{{ $t('masterLayout.recipes') }}</ion-label>
        </ion-tab-button>
      </ion-tab-bar>
    </ion-tabs>
  </ion-app>
</template>

<script>
import { IonApp, IonIcon, IonLabel, IonRouterOutlet, IonTabBar, IonTabButton, IonTabs } from '@ionic/vue';
import { defineComponent } from 'vue';
import { mapGetters } from 'vuex'
import axios from 'axios'
import { heart, list, restaurant } from 'ionicons/icons'

export default defineComponent({
  name: 'App',
  components: {
    IonApp,
    IonIcon,
    IonLabel,
    IonRouterOutlet,
    IonTabBar,
    IonTabButton,
    IonTabs,
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
          return Promise.reject(error)
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
  },
  setup() {
    return { heart, list, restaurant }
  },

});
</script>

<style>
ion-tab-bar {
  --background: var(--ion-background-color);
}

ion-tab-button {
  --color-selected: var(--ion-color-primary);
  --color: var(--ion-color-step-750);
}
</style>
