<template>    
  <master-layout :pageTitle="$t('home.title')">
    <div id="container">
        <ion-text color="primary">
          <h1>{{$t("home.title")}}</h1>
        </ion-text>
        <ion-text class="ion-padding-horizontal" color="primary">
          <p>{{$t("home.subtitle")}}</p>
          <p>{{$t("home.content")}}</p>
        </ion-text>
      <ion-button v-if="!$store.state.user" class="ion-margin-top" expand="block" @click="openSigninModal">Log in</ion-button>

      <ion-button v-if="!$store.state.user" class="ion-margin-top" expand="block" @click="openSignupModal">Registreren</ion-button>
    </div>
    <img src="../assets/logo-small.png" alt="">
  </master-layout>
</template>

<script>
import { IonButton, modalController, IonText } from '@ionic/vue'
import axios from 'axios'
import SigninModal from '../components/auth/SigninModal.vue'
import SignupModal from '../components/auth/SignupModal.vue'

export default{
  name: 'Home',
  components: {
    IonButton, IonText, 
  },
  ionViewDidEnter(){
    this.dashboardMessages()
  },
  data(){
    return {
      newMessages: undefined
    }
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
    },
    async dashboardMessages(){
      let lastCheck = JSON.parse(localStorage.getItem('user')).dashboard.lastCheck
      if(lastCheck && lastCheck < (Date.now() / 1000) -7200){
        console.log(lastCheck)
        console.log(Date.now()-7200)
        console.log(Date.now())
        axios.get('/dashboard')
        .then(response => (this.newMessages = response.data))
        .then(this.resetLastChecked())
        .catch(error => console.log(error))
      }
    },
    resetLastChecked(){
      let userObject = JSON.parse(localStorage.getItem('user'))
      userObject.dashboard.lastCheck = Date.now() / 1000
      localStorage.setItem('user', JSON.stringify(userObject))
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