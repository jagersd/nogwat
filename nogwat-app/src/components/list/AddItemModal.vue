<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>Voeg toe aan boodschappenlijst </ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-item>
        <ion-label position="stacked" color="primary">Product</ion-label>
        <ion-input type="text" required="true" v-model="form.listItems[0].itemName" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="stacked" color="secondary">Hoeveelheid</ion-label>
        <ion-input type="number" v-model="form.listItems[0].amount" id="amount"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="stacked" color="secondary">liter, gram, kilo</ion-label>
        <ion-select v-model="form.listItems[0].measurementType">
          <ion-select-option value="st">st (stuks)</ion-select-option>
          <ion-select-option value="gr">gr (gram)</ion-select-option>
          <ion-select-option value="kg">kg (kilo)</ion-select-option>
          <ion-select-option value="fl">fl (flessen)</ion-select-option>
          <ion-select-option value="kr">kr (kratten)</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item>
        <ion-label position="stacked" color="secondary">selecteer winkel</ion-label>
        <ion-select v-model="form.listItems[0].storeId">
          <ion-select-option v-for="store in storeArray" :key="store.id" :value="store.id">{{store.name}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item>
        <ion-label position="stacked" color="secondary">In huis halen voor</ion-label>
        <ion-datetime v-model="form.listItems[0].dueDate" display-format="DD/MM/YYYY" :year-values="customYearValues"></ion-datetime>
      </ion-item>
      <ion-button expand="fill" @click="addItem()">Opslaan</ion-button>
      <ion-button @click="closeModal">Sluit</ion-button>
    </ion-card-content>
  </ion-card>
</template>

<script>
import axios from 'axios'
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController, IonSelect, IonSelectOption, IonDatetime
} from "@ionic/vue";

import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'AddItemModal',
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton, IonSelect, IonSelectOption, IonDatetime
  },
  props: ['groupId', 'stores'],
  data() {
    return {
      storeArray: this.stores,
      form: {
        listItems: [{
          groupId: this.groupId,
          itemName: "",
          measurementType:"",
          amount: "",
          storeId:null,
          dueDate:null
        }]
      },
      errors: []
    };
  },
  setup() {
  const customYearValues = [new Date().getFullYear() ,new Date().getFullYear() +1];
  const closeModal = () => {
    modalController.dismiss();
    }
    return { closeModal, customYearValues }
  },
  methods: {
      addItem() {
      axios.post('/additem', this.form)
      .then(this.closeModal)

      .catch(error => {
      this.errorMessage = error.message;
      console.error("There was an error!", error);
      })
    }
  }
});
</script>