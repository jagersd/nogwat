<template>    
  <master-layout :pageTitle="$t('home.title')">
    <div id="container">
        <ion-text color="primary">
          <h1>{{$t("home.title")}}</h1>
        </ion-text>
        <ion-text class="ion-padding-horizontal" color="primary">
          {{$t("home.subtitle")}}
        </ion-text>

      <ion-button v-if="!$store.state.user" class="ion-margin-top" expand="block" @click="openSigninModal">Log in</ion-button>

      <ion-button v-if="!$store.state.user" class="ion-margin-top" expand="block" @click="openSignupModal">Registreren</ion-button>
    </div>
    <img src="../assets/logo-small.png" alt="">
  </master-layout>
</template>

<script>
import { IonButton, modalController, IonText } from '@ionic/vue'
import SigninModal from '../components/auth/SigninModal.vue'
import SignupModal from '../components/auth/SignupModal.vue'

export default{
  name: 'Home',
  components: {
    IonButton, IonText, 
  },

  methods: {
    async openSigninModal() {
      const modal = await modalController.create({
        component: SigninModal,
      })
      return modal.present();
    },
    async openSignupModal() {
      const modal = await modalController.create({
        component:SignupModal,
      })
      return modal.present();
    }    

  }

};
</script>

<style scoped>
#container {
  text-align: center;
  position: absolute;
  left: 0;
  right: 0;
  top: 30%;
}

#container strong {
  font-size: 20px;
  line-height: 26px;
}

#container p {
  font-size: 16px;
  line-height: 22px;
  color: #8c8c8c;
  margin: 0;
}

#container a {
  text-decoration: none;
}

img{
  margin-left: 40vw;
  bottom: 10vh;
  max-height: 30vh;
}
</style>