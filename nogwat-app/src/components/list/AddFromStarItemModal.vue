<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>Voeg opnieuw toe aan boodschappenlijst </ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-item>
        <ion-input :label="$t('shoppingList.addModal.product')" label-placement="floating" type="text" required="true" v-model="form.listItems[0].itemName" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-input :label="$t('shoppingList.addModal.amount')" label-placement="stacked" type="number" v-model="form.listItems[0].amount" id="amount"></ion-input>
      </ion-item>
      <ion-item>
        <ion-select :label="$t('shoppingList.addModal.amountExamples')" label-placement="stacked" v-model="form.listItems[0].measurementType" interface="popover">
          <ion-select-option value="st">{{$t('misc.measurements.pc')}}</ion-select-option>
          <ion-select-option value="pk">{{$t('misc.measurements.pk')}}</ion-select-option>
          <ion-select-option value="gr">{{$t('misc.measurements.gr')}}</ion-select-option>
          <ion-select-option value="kg">{{$t('misc.measurements.kg')}}</ion-select-option>
          <ion-select-option value="fl">{{$t('misc.measurements.bl')}}</ion-select-option>
          <ion-select-option value="l">{{$t('misc.measurements.l')}}</ion-select-option>
          <ion-select-option value="kr">{{$t('misc.measurements.ca')}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item v-if="storeArray.length">
        <ion-select :label="$t('shoppingList.addModal.selectStore')" label-placement="stacked" v-model="form.listItems[0].storeId" interface="popover">
          <ion-select-option v-for="store in storeArray" :key="store.id" :value="store.id">{{store.name}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-button expand="fill" @click="addItem()">{{$t('misc.save')}}</ion-button>
      <ion-button @click="closeModal">{{$t('misc.close')}}</ion-button>
    </ion-card-content>
  </ion-card>
</template>

<script>
import axios from 'axios'
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonInput,IonButton,modalController, IonSelect, IonSelectOption, toastController
} from "@ionic/vue";


export default{
  name: 'AddFromStarItem',
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonInput,IonButton, IonSelect, IonSelectOption
  },
  props: ['groupId', 'stores', 'listItem'],
  updated(){
    this.form = this.initForm()
  },
  data() {
    return {
      storeArray: this.stores,
      form: this.initForm(),
      errors: []
    };
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
    closeModal(){
      this.form = this.initForm()
      modalController.dismiss()
    },
    initForm(){
      return{
        listItems: [{
          groupId: this.groupId,
          itemName: this.listItem,
          measurementType:"",
          amount: "",
          storeId:null,
        }]
      }
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
};
</script>
