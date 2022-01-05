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
        <ion-input type="number" v-model="form.listItems[0].amount" @change="autoFillMeasurement" id="amount"></ion-input>
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
      <ion-item v-if="storeArray.length">
        <ion-label position="stacked" color="secondary">selecteer winkel</ion-label>
        <ion-select v-model="form.listItems[0].storeId">
          <ion-select-option v-for="store in storeArray" :key="store.id" :value="store.id">{{store.name}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item button="true" id="open-date-input" lines="none">
        <ion-label position="stacked" color="secondary">In huis halen voor</ion-label>
        <ion-input :value="formattedDate"/>
        <ion-modal trigger="open-date-input">
          <ion-content force-overscroll="false">
          <ion-datetime v-model="form.listItems[0].dueDate" 
          first-day-of-week="1" 
          presentation="date" 
          format="YYYY-MM-DD"
          :year-values="customYearValues"
          />
          </ion-content>
        </ion-modal>
      </ion-item>
      <ion-button expand="fill" @click="addItem()">Opslaan</ion-button>
      <ion-button @click="closeModal">Sluit</ion-button>
    </ion-card-content>
  </ion-card>
</template>

<script>
import axios from 'axios'
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController, IonSelect, IonSelectOption, IonDatetime, IonModal, IonContent
} from "@ionic/vue"
import moment from 'moment'
import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'AddItemModal',
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton, IonSelect, IonSelectOption, IonDatetime, IonModal, IonContent
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
      errors: [],
    };
  },
  setup() {
    const customYearValues = [new Date().getFullYear() ,new Date().getFullYear() +1];
    const closeModal = () => {
    modalController.dismiss(undefined,undefined,'add-item-modal');
    }
    return { closeModal, customYearValues }
  },
  computed:{
    formattedDate: function(){
      return this.form.listItems[0].dueDate ? moment(this.form.listItems[0].dueDate).format('D MMM YYYY') : null
    }
  },
  methods: {
    addItem() {
      axios.post('/additem', this.form)
      .then(this.closeModal)

      .catch(error => {
      this.errorMessage = error.message;
      console.error("There was an error!", error);
      })
    },
    autoFillMeasurement() {
      this.form.listItems[0].measurementType = (this.form.listItems[0].amount) ? 'st' : ''
    },
  }
});
</script>

<style scoped>
  ion-modal {
    --width: 290px;
    --height: 382px;
    --border-radius: 10px;
    
  }
  ion-datetime{
    --background: 'dark';
    border: 2px solid #1e847f;
    border-radius: 10px;
  }
</style>