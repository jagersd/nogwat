<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>Reset wachtwoord</ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-item v-if="!tokenGenerated">
        <ion-input label="Email" label-placement="floating" type="email" name="email" v-model="email"></ion-input>
      </ion-item>
      <ion-item v-if="tokenGenerated == 200">
        <p>Er is een token naar je email adress verstuurd</p>
        <ion-input label="Token" label-placement="floating" type="number" name="token" v-model="token"></ion-input>
      </ion-item>
      <ion-item v-if="tokenGenerated == 200">
        <ion-input label="Nieuw Wachtwoord" label-placement="floating" type="password" name="new-password" v-model="newPassword"></ion-input>
      </ion-item>
      <ion-item v-if="tokenGenerated == 200">
        <ion-input label="Herhaal nieuw Wachtwoord" label-placement="floating" type="password" name="new-password-checker" v-model="newPasswordChecker"></ion-input>
      </ion-item>
      <ion-item lines="none" color="primary" v-if="tokenError==201">
        <p>Wachtwoord is succesvol geupdate. Je kunt nu inloggen met je nieuwe wachtwoord</p>
      </ion-item>
      <ion-item color="danger" v-if="tokenError!=='' && tokenError!==201">
        <p>Het token was verlopen of incorrect</p>
      </ion-item>
      <ion-button v-if="!tokenGenerated" @click="submitEmail">ok</ion-button>
      <ion-button v-if="tokenGenerated==200" @click="submitToken">ok</ion-button>
      <ion-button @click="closeModal">Sluiten</ion-button>
    </ion-card-content>
  </ion-card>
</template>

<script>
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonInput,IonButton,modalController,
} from "@ionic/vue";
import { defineComponent } from 'vue'
import axios from 'axios'

export default defineComponent({
  name: 'ResetPasswordModal',
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonInput,IonButton,
  },
  data () {
    return {
      email: '',
      tokenGenerated: null,
      token: null,
      tokenError: '',
      newPassword: '',
      newPasswordChecker: '',
    }
  },
  setup() {
    const closeModal = () => {
      modalController.dismiss();
    }
    return { closeModal }
  },
  methods: {
    async submitEmail(){
      if(this.email !== ''){
        axios.post('forgotpassword', {email:this.email})
        .then(response => this.tokenGenerated = response.status)
      }
    },
    async submitToken(){
      if(this.token !== null && this.newPassword == this.newPasswordChecker){
        axios.post('submitresettoken',{email:this.email, token:this.token, newPassword:this.newPassword})
        .then(response=> this.tokenError = response.status)
        .catch(error=>{
          this.tokenError = error.status
        })
      }
    }
  }
});
</script>
