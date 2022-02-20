<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>Voeg opnieuw toe aan boodschappenlijst </ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-item>
        <ion-label position="floating">Product</ion-label>
        <ion-input type="text" required="true" v-model="form.listItems[0].itemName" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">Hoeveelheid</ion-label>
        <ion-input type="number" v-model="form.listItems[0].amount" id="amount"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="stacked">liter, gram, kilo</ion-label>
        <ion-select v-model="form.listItems[0].measurementType" interface="popover">
          <ion-select-option value="st">st (stuks)</ion-select-option>
          <ion-select-option value="gr">gr (gram)</ion-select-option>
          <ion-select-option value="kg">kg (kilo)</ion-select-option>
          <ion-select-option value="fl">fl (flessen)</ion-select-option>
          <ion-select-option value="kr">kr (kratten)</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item v-if="storeArray.length">
        <ion-label position="stacked">(optioneel) selecteer winkel</ion-label>
        <ion-select v-model="form.listItems[0].storeId" interface="popover">
          <ion-select-option v-for="store in storeArray" :key="store.id" :value="store.id">{{store.name}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-button expand="fill" @click="addItem()">Opslaan</ion-button>
      <ion-button @click="closeModal">Sluit</ion-button>
    </ion-card-content>
  </ion-card>
</template>

<script>
import axios from 'axios'
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController, IonSelect, IonSelectOption, toastController
} from "@ionic/vue";

import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'AddFromStarItem',
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton, IonSelect, IonSelectOption
  },
  props: ['groupId', 'stores', 'listItem'],
  data() {
    return {
      storeArray: this.stores,
      form: {
        listItems: [{
          groupId: this.groupId,
          itemName: this.listItem,
          measurementType:"",
          amount: "",
          storeId:null,
        }]
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
    addItem() {
      axios.post('/additem', this.form)
      .then(this.closeModal)
      .then(this.toastResponse)

      .catch(error => {
      this.errorMessage = error.message;
      console.error("There was an error!", error);
      })
    },
    async toastResponse(){
      const toast = await toastController
      .create({
        message: this.form.listItems[0].itemName + ' toegevoegd!',
        color: 'primary',
        position: 'bottom',
        duration: 2000
      })
      return toast.present()
    }
  }
});
</script>