<template>
  <ion-page id="main">
    <ion-header>
      <ion-toolbar>
        <ion-button id="home-icon" href="/" shape="round" slot="start">
        <ion-icon :icon="home" slot="start"></ion-icon>
        </ion-button>
        <ion-title>{{ pageTitle }}</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <ion-fab v-if="$store.state.user" horizontal="end" vertical="top" slot="fixed" edge>
        <ion-fab-button color="primary">
          <ion-icon :icon="person"></ion-icon>
        </ion-fab-button>
        <ion-fab-list side="bottom">
          <ion-fab-button router-link="/usersettings" color="secondary">
            <ion-icon :icon="settings"></ion-icon>
          </ion-fab-button>
          <ion-fab-button color="warning">
            <ion-icon :icon="star"></ion-icon>
          </ion-fab-button>
          <ion-fab-button color="danger">
            <ion-icon :icon="close" @click="presentActionSheet"></ion-icon>
          </ion-fab-button>
        </ion-fab-list>
        <ion-fab-list side="start">
          <ion-fab-button color="tertiary" router-link="/about">
            <ion-icon :icon="help"></ion-icon>
          </ion-fab-button>
        </ion-fab-list>
      </ion-fab>
      <slot> </slot>
    </ion-content>

    <ion-tab-bar v-if="$store.state.user" slot="bottom">

      <ion-tab-button tab="Groups" href="/groups">
        <ion-icon :icon="heart" size="large"></ion-icon>
        <ion-label>Groepen</ion-label>
      </ion-tab-button>

      <ion-tab-button tab="Lists" href="/lists">
        <ion-icon :icon="list" size="large"></ion-icon>
        <ion-label>Boodschappenlijst</ion-label>
      </ion-tab-button>

      <ion-tab-button tab="Recipes" href="/recipes">
        <ion-icon :icon="flame" size="large"></ion-icon>
        <ion-label>Recepten</ion-label>
      </ion-tab-button>
    </ion-tab-bar>
  </ion-page>
</template>
<script>
import {
  IonPage,IonHeader,IonContent,IonToolbar,IonTitle, IonIcon, IonLabel, IonTabBar, IonTabButton, IonFab, 
  IonFabButton, 
  IonFabList,
  actionSheetController,
  IonButton
} from "@ionic/vue";

import { heart, list, flame, person, close, settings, star, help, home } from "ionicons/icons";
export default {
  components: {
    IonPage,IonHeader,IonContent,IonToolbar,IonTitle, IonIcon, IonLabel, IonTabBar, IonTabButton, IonFab, IonFabButton, IonFabList, IonButton
  },
  setup() {
    return {
      heart,list, flame, person, close, settings, star, help, home
    }
  },
  props: ["pageTitle"],

  methods: {
    async presentActionSheet(){
      const actionSheet = await actionSheetController
      .create({
        header: 'Uitloggen?',
        buttons: [
          {
            text: 'Ja',
            handler: () => {
              this.$store.dispatch('logout')
              window.localStorage.clear()
              .then(this.$router.push({ path: '/'}))
            }
          },
          {
            text: 'Nee',
            role: 'cancel'
          }
        ]
      })
      await actionSheet.present()
    },
  },
};

</script>
<style scoped>
ion-toolbar{
    --background: var(--ion-color-primary);
    --color: var(--ion-color-primary-contrast);
}
</style>