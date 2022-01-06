<template>
  <div class="container">
  <ion-card>
    <ion-card-header>
      <ion-card-title>{{$t('misc.adjust')}}</ion-card-title>
    </ion-card-header>
    <ion-card-content>
      <ion-text>
        {{$t('shoppingList.addModal.addedBy')}} {{itemDetails.added_user.name}}
      </ion-text>
      <ion-item>
        <ion-label position="floating">{{$t('shoppingList.addModal.product')}}</ion-label>
        <ion-input type="text" required="true" v-model="form.itemName" id="name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating" color="secondary">{{$t('shoppingList.addModal.amount')}}</ion-label>
        <ion-input type="number" required="true" v-model="form.amount" id="amount"></ion-input>
      </ion-item>
      <ion-item>
      <ion-label position="floating" color="secondary">{{$t('shoppingList.addModal.amountExamples')}}</ion-label>
        <ion-select v-model="form.measurementType">
          <ion-select-option value="st">{{$t('misc.measurements.pc')}}</ion-select-option>
          <ion-select-option value="gr">{{$t('misc.measurements.gr')}}</ion-select-option>
          <ion-select-option value="kg">{{$t('misc.measurements.kg')}}</ion-select-option>
          <ion-select-option value="fl">{{$t('misc.measurements.bl')}}</ion-select-option>
          <ion-select-option value="kr">{{$t('misc.measurements.ca')}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item v-if="storeArray.length">
        <ion-label position="stacked" color="secondary">{{$t('shoppingList.addModal.selectStore')}}</ion-label>
        <ion-select v-model="form.storeId">
          <ion-select-option v-for="store in storeArray" :key="store.id" :value="store.id">{{store.name}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item button="true" id="open-date-input" lines="none"> 
        <ion-label position="stacked" color="secondary">{{$t('shoppingList.addModal.dueDesc')}}</ion-label>
        <ion-input :value="formattedDate"/>
        <ion-modal trigger="open-date-input">
          <ion-content force-overscroll="false">
            <ion-datetime v-model="form.dueDate"
            first-day-of-week="1" 
            presentation="date" 
            :year-values="customYearValues"
            />
          </ion-content>
        </ion-modal>
        
      </ion-item>
      <ion-button expand="fill" @click="updateItem()">{{$t('misc.save')}}</ion-button>
      <ion-button @click="closeModal">{{$t('misc.close')}}</ion-button>
      <ion-button v-if="(currentUserMail == itemDetails.added_user.email)" @click="deleteItem(itemDetails.id)" color="danger">{{$t('misc.delete')}}</ion-button>
    </ion-card-content>
  </ion-card>
  </div>
</template>

<script>
import axios from 'axios'
import { IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController, IonSelect, IonSelectOption, IonText, IonDatetime,IonModal, IonContent
} from "@ionic/vue";
import moment from 'moment'
import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'ItemDetailsModal',
    components: { IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,IonSelect, IonSelectOption, IonText, IonDatetime,IonModal, IonContent
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
    modalController.dismiss(undefined,undefined,'item-detail-modal');
    }
  return { closeModal, customYearValues }
  },
  computed:{
    formattedDate: function(){
      return this.form.dueDate ? moment(this.form.dueDate).format('D MMM YYYY') : null
    }
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

<style scoped>

  .container{
    overflow-x: scroll;
  }

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