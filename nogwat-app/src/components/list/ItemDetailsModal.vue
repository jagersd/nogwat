<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>Aanpassen</ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-text>
        Toevoegd door: {{itemDetails.added_user.name}}
      </ion-text>
      <ion-item>
        <ion-label position="floating">Product</ion-label>
        <ion-input type="text" required="true" v-model="form.itemName" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating" color="secondary">Hoeveelheid</ion-label>
        <ion-input type="number" required="true" v-model="form.amount" id="amount"></ion-input>
      </ion-item>
      <ion-item>
      <ion-label position="floating" color="secondary">liter, gram, kilo</ion-label>
        <ion-select v-model="form.measurementType">
          <ion-select-option value="st">st (stuks)</ion-select-option>
          <ion-select-option value="gr">gr (gram)</ion-select-option>
          <ion-select-option value="kg">kg (kilo)</ion-select-option>
          <ion-select-option value="fl">fl (flessen)</ion-select-option>
          <ion-select-option value="kr">kr (kratten)</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item v-if="storeArray.length">
        <ion-label position="stacked" color="secondary">(optioneel) selecteer winkel</ion-label>
        <ion-select v-model="form.storeId">
          <ion-select-option v-for="store in storeArray" :key="store.id" :value="store.id">{{store.name}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item>
        <ion-label position="stacked" color="secondary">In huis halen voor</ion-label>
        <ion-datetime v-model="form.dueDate" display-format="DD/MM/YYYY" :year-values="customYearValues"></ion-datetime>
      </ion-item>
      <ion-button expand="fill" @click="updateItem()">Opslaan</ion-button>
      <ion-button @click="closeModal">Sluit</ion-button>
      <ion-button v-if="(currentUserMail == itemDetails.added_user.email)" @click="deleteItem(itemDetails.id)" color="danger">Verwijderen</ion-button>
    </ion-card-content>
  </ion-card>
</template>

<script>
import axios from 'axios'
import { IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController, IonSelect, IonSelectOption, IonText, IonDatetime} from "@ionic/vue";

import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'ItemDetailsModal',
    components: { IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,IonSelect, IonSelectOption, IonText, IonDatetime
  },

  props: ['itemDetails','stores'],

  data() {
    return {
      currentUserMail: this.$store.state.user.user.email,
      storeArray: this.stores, 
      form: {
        listItemId: this.itemDetails.id,
        itemName: this.itemDetails.item_name,
        measurementType:this.itemDetails.measurement.abbreviation,
        amount: this.itemDetails.measurement_amount,
        storeId: this.itemDetails.store_id,
        dueDate: this.itemDetails.due_date
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
    updateItem() {
      axios.put('/updateitem', this.form)
      .then(this.closeModal)
      .catch(error => {
      this.errorMessage = error.message;
      console.error("There was an error!", error);
      })
    },
    deleteItem(itemId) {
      axios.delete('/removelistitem', {data: {
        'id':itemId
        }
      })
      .then(this.closeModal)
      .catch(error => {
      this.errorMessage = error.message;
      console.error("There was an error!", error);
      })
    }
  }
});
</script>