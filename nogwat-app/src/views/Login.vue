<template>
  <master-layout pageTitle="Login Form">
    <ion-card>
      <ion-card-header>
        <ion-card-title>SignIn</ion-card-title>
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
      </ion-card-content>
    </ion-card>
  </master-layout>
</template>

<script>
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton
} from "@ionic/vue";

export default {
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton
  },
  data () {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login () {
      this.$store
        .dispatch('login', {
          email: this.email,
          password: this.password
        })
        .then(() => {
          this.$router.push({ path: '/about' })
        })
        .catch(err => {
          console.log(err)
        })
    }
  }
};
</script>