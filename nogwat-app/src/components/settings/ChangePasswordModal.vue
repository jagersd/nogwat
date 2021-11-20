<template>
  
  <div class="container">
    <ion-item>
      <ion-label position="floating">Huidig wachtwoord</ion-label>
      <ion-input v-model="form.password" type="password"></ion-input>
    </ion-item>
    <ion-item>
      <ion-label position="floating">Nieuw wachtwoord</ion-label>
      <ion-input v-model="form.newPassword" type="password"></ion-input>
    </ion-item>
    <ion-item>
      <ion-label position="floating">Herhaal nieuw wachtwoord</ion-label>
      <ion-input v-model="repeatedPassword" type="password"></ion-input>
    </ion-item>
  </div>
  <ion-button color="primary" @click="changePassword"> OK </ion-button>   
  <ion-button color="danger" @click="closeModal">Sluit</ion-button>
</template>

<script>
import {IonItem, IonLabel, IonInput, IonButton, modalController} from "@ionic/vue"
import { defineComponent } from 'vue'
import axios from 'axios'

export default defineComponent({
  name:"ChangePasswordModal",
  components: {
    IonItem, IonLabel, IonInput, IonButton
  },
  data(){
    return{
      repeatedPassword: "",
      form:{
        email: JSON.parse(localStorage.getItem("user")).user.email,
        password: '',
        newPassword: '',
      },
      apiResponse: ''
    }
  },
  methods: {
    closeModal(){
      modalController.dismiss()
    },
    changePassword(){
      if(this.repeatedPassword == this.form.newPassword){
        axios.post('changepassword', this.form)
        .then(response => this.apiResponse = response.data)
        .catch(error=> {
          this.errorMessage = error.errorMessage
          console.error("There was an error!", error)
        })
      }
    }
  }


})
</script>

<style scoped>
ion-item{
  margin-top: 5vh;
}
</style>