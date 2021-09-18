<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>Signup</ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-item>
        <ion-label position="floating">Groep naam</ion-label>
        <ion-input type="text" required="true" v-model="form.name" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">Omschrijving</ion-label>
        <ion-input type="admin_instructions" required="true" v-model="form.admin_instructions" id="email"></ion-input>
      </ion-item>
      <ion-button expand="fill" @click="createGroup()">Groep aanmaken</ion-button>
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
  name: 'CreatGroupModal',
    components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton
  },
  data() {
    return {
      form: {
        name: "",
        admin_instructions: "",
      },
      errors: []
    };
  },
  setup() {
  const closeModal = () => {
    modalController.dismiss();
  }
  return { closeModal }
  },
  methods: {
        createGroup() {
        axios.post('/creategroup', this.form)
        .then(response => this.articleId = response.data.id)
        .then(this.closeModal)
        .catch(error => {
        this.errorMessage = error.message;
        console.error("There was an error!", error);
        });
    }
  }
});
</script>