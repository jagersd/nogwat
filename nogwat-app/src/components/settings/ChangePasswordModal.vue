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
import {IonItem, IonLabel, IonInput, IonButton, modalController, toastController} from "@ionic/vue"
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
      toastMessages: {
        incorrectRepeat:'De wachtwoorden zijn niet gelijk',
        sameAsOriginal:'Het nieuwe wachtwoord is gelijk aan de oude',
        success: 'Het wachtwoord is succesvol gewijzigd',
        error: 'Er is iets fout gegaan, wachtwoord niet gewijzigd',
      },
      apiResponse: ''
    }
  },
  watch:{
    apiResponse(){
      this.showApiResponse()
    }
  },
  methods: {
    closeModal(){
      modalController.dismiss()
    },
    async changePassword(){
      if(this.repeatedPassword != this.form.newPassword){
        const toast = await toastController
        .create({
          message: this.toastMessages.incorrectRepeat,
          color: 'dark',
          position: 'top',
          duration: 2000
        })
        return toast.present();
      } else if (this.repeatedPassword == this.form.password){
        const toast = await toastController
        .create({
          message: this.toastMessages.sameAsOriginal,
          color: 'dark',
          position: 'top',
          duration: 2000
        })
        return toast.present();
      } else {
        axios.post('changepassword', this.form)
        .then(response => this.apiResponse = response.data)
        .catch(error => {
          this.apiResponse = {message:'error'}
          this.errorMessage = error.errorMessage
          console.error("There was an error!", error)
        })
      }
    },
    async showApiResponse(){
      if(this.apiResponse.message == 'Password Changed'){
        const toast = await toastController
        .create({
          message: this.toastMessages.success,
          color:'dark',
          position:'top',
          duration: 2000
        })
        return toast.present()
      } else {
        const toast = await toastController
        .create({
          message: this.toastMessages.error,
          color: 'dark',
          position: 'top',
          duration: 2000
        })
        return toast.present()
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