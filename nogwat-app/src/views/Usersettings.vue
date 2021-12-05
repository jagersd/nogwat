<template>
  <master-layout pageTitle="Instellingen">
    <div class="container">
      <ion-text>
        <p>naam: {{userInfo.user.name}}</p>
        <p>e-mail: {{userInfo.user.email}}</p>
      </ion-text>
      <ion-item @click="openSelectLocaleModal">
        <ion-icon :icon="language" slot="start"></ion-icon>
        <ion-label>Selecteer App taal</ion-label>
      </ion-item>
      <ion-item @click="openChangePasswordModal">
        <ion-icon :icon="key" slot="start"></ion-icon>
        <ion-label>Wijzig wachtwoord</ion-label>
      </ion-item>
      <ion-item @click="openSendFeedbackModal">
        <ion-icon :icon="alertCircle" slot="start"></ion-icon>
        <ion-label>Stuur bug report / feedback</ion-label>
      </ion-item>
    </div>
  </master-layout>
</template>

<script>
import {
  IonItem, IonLabel,IonIcon,IonText, modalController
} from "@ionic/vue"
import SelectLocaleModal from '../components/settings/SelectLocaleModal.vue'
import ChangePasswordModal from '../components/settings/ChangePasswordModal.vue'
import SendFeedbackModal from '../components/settings/sendFeedback.vue'

import { language, key, alertCircle } from "ionicons/icons";

export default {
  components: {IonItem, IonLabel, IonIcon, IonText},
  name: "Settings",

  setup(){
  return {language, key, alertCircle}
  },
  data(){
    return{
      userInfo: JSON.parse(localStorage.getItem('user'))
    }
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
    async openSendFeedbackModal(){
      const modal = await modalController.create({
        component: SendFeedbackModal
      })
      return modal.present()
    }

  }


}

</script>

<style scoped>

ion-text p{
  color: var(--ion-color-primary);
  margin-left: 2vw;
}

ion-item {
  margin-top: 5vh;
  --color: var(--ion-color-primary);
}
</style>