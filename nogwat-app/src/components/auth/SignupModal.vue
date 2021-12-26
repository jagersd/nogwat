<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>Signup</ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-item>
        <ion-label position="floating">Gebruikersnaam</ion-label>
        <ion-input type="text" required="true" v-model="form.name" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">Email adres</ion-label>
        <ion-input type="email" required="true" v-model="form.email" id="email"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">Wachtwoord</ion-label>
        <ion-input type="password" required="true" v-model="form.password" id="password"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">Wachtwoord confirmatie</ion-label>
        <ion-input type="password" required="true" v-model="form.password_confirmation" id="password_confirmation"></ion-input>
      </ion-item>
      <ion-item lines="none" color="danger" id="error-message" v-if="errorMessage != ''">
        {{errorMessage}}
      </ion-item>
      <ion-button expand="fill" @click="register()">Register</ion-button>
      <ion-button @click="closeModal">Sluit</ion-button>
    </ion-card-content>
  </ion-card>
</template>

<script>
import axios from 'axios'
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController
} from "@ionic/vue";

import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'SignupModal',
    components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton
  },
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      },
      errors: [],
      errorMessage: ""
    };
  },
  setup() {
  const closeModal = () => {
    modalController.dismiss();
  }
  return { closeModal }
  },
  methods: {
      register() {
        if(this.form.password != this.form.password_confirmation){
          this.errorMessage = 'Passwords do not match'
        } else {
          axios.post('/register', this.form)
          .then(response => this.articleId = response.data.id)
          .then(this.closeModal)
          .catch(error => {
          error.response.status == 409 ? this.errorMessage = 'User already registered' : this.errorMessage = error.message
          console.error("There was an error!", error);
        });
      }
    }
  }
});
</script>

<style scoped>
  #error-message{
    margin-top: 1rem;
    border-radius: 10px;
  }
</style>