<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>{{$t('shoppingList.addModal.titel')}}</ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-item>
        <ion-label position="stacked" color="primary"><b>{{$t('shoppingList.addModal.product')}}</b></ion-label>
        <ion-input type="text" required="true" v-model="form.listItems[0].itemName" maxlength="35" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="stacked" color="secondary">{{$t('shoppingList.addModal.amount')}}</ion-label>
        <ion-input type="number" v-model="form.listItems[0].amount" @change="autoFillMeasurement" id="amount"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="stacked" color="secondary">{{$t('shoppingList.addModal.amountExamples')}}</ion-label>
        <ion-select v-model="form.listItems[0].measurementType" interface="popover">
          <ion-select-option value="st">{{$t('misc.measurements.pc')}}</ion-select-option>
          <ion-select-option value="pk">{{$t('misc.measurements.pk')}}</ion-select-option>
          <ion-select-option value="gr">{{$t('misc.measurements.gr')}}</ion-select-option>
          <ion-select-option value="kg">{{$t('misc.measurements.kg')}}</ion-select-option>
          <ion-select-option value="fl">{{$t('misc.measurements.bl')}}</ion-select-option>
          <ion-select-option value="kr">{{$t('misc.measurements.ca')}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item v-if="storeArray.length">
        <ion-label position="stacked" color="secondary">{{$t('shoppingList.addModal.selectStore')}}</ion-label>
        <ion-select v-model="form.listItems[0].storeId" interface="popover">
          <ion-select-option v-for="store in storeArray" :key="store.id" :value="store.id">{{store.name}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item button="true" id="open-date-input" lines="none">
        <ion-label position="stacked" color="secondary">{{$t('shoppingList.addModal.dueDesc')}}</ion-label>
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
      <ion-button expand="fill" @click="addItem()">{{$t('misc.save')}}</ion-button>
      <ion-button @click="closeModal">{{$t('misc.close')}}</ion-button>
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