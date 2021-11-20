<template>
  <master-layout pageTitle="Instellingen">
    <div class="container">
      <ion-item @click="openSelectLocaleModal">
        <ion-icon :icon="language" slot="start"></ion-icon>
        <ion-label>Selecteer App taal</ion-label>
      </ion-item>
      <ion-item @click="openChangePasswordModal">
        <ion-icon :icon="key" slot="start"></ion-icon>
        <ion-label>Wijzig wachtwoord</ion-label>
      </ion-item>
    </div>
  </master-layout>
</template>

<script>
import {
  IonItem, IonLabel,IonIcon, modalController
} from "@ionic/vue"
import SelectLocaleModal from '../components/settings/SelectLocaleModal.vue'
import ChangePasswordModal from '../components/settings/ChangePasswordModal.vue'

import { language, key } from "ionicons/icons";

export default {
  components: {IonItem, IonLabel, IonIcon},
  name: "Settings",

  setup(){
  return {language, key}
  },

  methods: {
    async openSelectLocaleModal(){
      const modal = await modalController.create({
        component: SelectLocaleModal
      })
      return modal.present()
    },
    async openChangePasswordModal(){
      const modal = await modalController.create({
        component: ChangePasswordModal
      })
      return modal.present()
    },

  }


}



</script>

<style scoped>

ion-item {
  margin-top: 5vh;
  --color: var(--ion-color-primary);
}
</style>