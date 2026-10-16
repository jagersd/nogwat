<template>
  <ion-page id="main">
    <ion-header>
      <ion-toolbar>
        <ion-button id="home-icon" router-link="/" shape="round" slot="start">
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
          <ion-fab-button router-link="/staritems" color="warning">
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
  </ion-page>
</template>
<script>
import {
  IonPage,
  IonHeader,
  IonContent,
  IonToolbar,
  IonTitle, 
  IonIcon, 
  IonLabel,
  IonFab, 
  IonFabButton, 
  IonFabList,
  actionSheetController,
  IonButton
} from "@ionic/vue";

import { person, close, settings, star, help, home } from "ionicons/icons";
export default {
  components: {
    IonPage,IonHeader,IonContent,IonToolbar,IonTitle, IonIcon, IonLabel, IonFab, IonFabButton, IonFabList, IonButton
  },
  setup() {
    return {
      person, close, settings, star, help, home
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
              .then(this.$router.push({ name: 'home'}))
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
