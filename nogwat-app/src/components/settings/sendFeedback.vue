<template>
  <div class="container">
    <ion-list>
      <ion-radio-group v-model="form.type" value="Bug">
        <ion-label><h1>Selecteer soort feedback</h1></ion-label>
        <ion-item>
          <ion-label>Bug</ion-label>
          <ion-radio slot="start" value="Bug"></ion-radio>
        </ion-item>

        <ion-item>
          <ion-label>Missing Feature</ion-label>
          <ion-radio slot="start" value="Missing Feature"></ion-radio>
        </ion-item>

        <ion-item>
          <ion-label>Other</ion-label>
          <ion-radio slot="start" value="Other"></ion-radio>
        </ion-item>
      </ion-radio-group>
    </ion-list>
    <ion-item>
      <ion-label id="content-label" position="stacked">omschrijving</ion-label>
      <ion-textarea v-model="form.content" autoGrow="true" inputmode="text" spellcheck="true">
      </ion-textarea> 
    </ion-item>

    <ion-button @click="sendFeedback">Verstuur</ion-button>
    <ion-button @click="closeModal">Terug</ion-button>
  </div>
</template>

<script>
import {IonItem, IonLabel,IonTextarea, IonButton, modalController, IonRadio, IonRadioGroup, IonList, toastController } from "@ionic/vue"
import { defineComponent } from 'vue'
import axios from 'axios'
export default defineComponent({
  name:"SendFeedbackModal",
  components:{
    IonItem, IonLabel, IonTextarea, IonButton, IonRadio, IonRadioGroup, IonList
  },
  data(){
    return{
      form:{
        type:'Bug',
        content:'',
      }
    }
  },
  methods:{
    closeModal(){
      modalController.dismiss()
    },
    sendFeedback(){
      axios.post('sendfeedback',this.form)
      .then(this.toastResponse)
      .then(this.closeModal)
      .catch(error => {
          this.apiResponse = {message:'error'}
          this.errorMessage = error.errorMessage
          console.error("There was an error!", error)
      })
    },
    async toastResponse(){
      const toast = await toastController
      .create({
        message:'Message sent',
        color: 'dark',
        position: 'top',
        duration: 2000
      })
      return toast.present()
    }
  }
})
</script>
<style scoped>
.container{
  margin-top:10vh;
}

h1{
  margin-left: 10px;
  color:black;
}

#content-label{
  margin-top: 20px;
}

ion-textarea{
  min-height: 30vh;
}

</style>