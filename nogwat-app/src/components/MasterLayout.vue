<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>{{ pageTitle }}</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <slot> </slot>
    </ion-content>
      <ion-tab-bar slot="bottom">

        <ion-tab-button tab="Dashboard" href="/dashboard">
          <ion-icon :icon="heart" size="large"></ion-icon>
          <ion-label>Groupen</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="Dashboard" href="/dashboard">
          <ion-icon :icon="list" size="large"></ion-icon>
          <ion-label>Boodschappenlijst</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="Dashboard" href="/dashboard">
          <ion-icon :icon="flame" size="large"></ion-icon>
          <ion-label>Recepten</ion-label>
        </ion-tab-button>
      </ion-tab-bar>
  </ion-page>
</template>
<script>
import {
  IonPage,IonHeader,IonContent,IonToolbar,IonTitle, IonIcon, IonLabel, IonTabBar, IonTabButton, 
} from "@ionic/vue";

import { heart, list, flame } from "ionicons/icons";
export default {
  components: {
    IonPage,IonHeader,IonContent,IonToolbar,IonTitle, IonIcon, IonLabel, IonTabBar, IonTabButton, 
  },
  setup() {
    return {
      heart,list, flame
    }
  },
  props: ["pageTitle"],
  
};
</script>
<style scoped>
ion-toolbar{
    --background: var(--ion-color-primary);
    --color: var(--ion-color-primary-contrast)
}
</style>