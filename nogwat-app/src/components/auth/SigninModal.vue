<template>
    <ion-card>
      <ion-card-header>
        <ion-card-title>Inloggen</ion-card-title>
      </ion-card-header>
      <ion-card-content>
        <ion-item>
          <ion-label position="floating">Email address</ion-label>
          <ion-input type="email" name="email" v-model="email"></ion-input>
        </ion-item>
        <ion-item>
          <ion-label position="floating">Password</ion-label>
          <ion-input type="password" name="password" v-model="password"></ion-input>
        </ion-item>
        <ion-button expand="full" @click="login()">Login</ion-button>
        <ion-button @click="closeModal">Sluit</ion-button>
      </ion-card-content>
    </ion-card>
</template>

<script>
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController
} from "@ionic/vue";
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'SigninModal',
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton
  },
  data () {
    return {
      email: '',
      password: ''
    }
  },
  setup() {
    const closeModal = () => {
      modalController.dismiss();
    }
    return { closeModal }
  },
  methods: {
    login () {
      this.$store
        .dispatch('login', {
          email: this.email,
          password: this.password
        })
        .then(this.closeModal)
        .then(() => {
          this.$router.push({ path: '/lists' })
        })
        .catch(err => {
          console.log(err)
        })
    },
  }
});
</script>