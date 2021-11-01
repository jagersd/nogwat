<template>
  <master-layout pageTitle="Boodschappenlijst">
    <div class="container">
      <div v-if="!listInfo.length">
        <ion-text>Cooking with fire! Je boodschappenlijst is leeg :)</ion-text>
      </div>
      <ion-list id="main-content" v-for="listGroup in listInfo" :key="listGroup.id">
        <ion-label>
          <ion-button @click="listShown = listGroup.id">{{ listGroup.name }}
						<ion-badge color="danger">{{listGroup.active_lists.filter(item => item.user_id_purchased == null).length}}</ion-badge>
					</ion-button>
          <ion-list v-for="listItem in listGroup.active_lists" :key="listItem.id">
            <ion-item v-if="listGroup.id == listShown" >
              <ion-label>
                <h2 v-if="listItem.date_purchased != null" @click="reversePurchasedActionSheet(listItem.id)">
                  <s>{{ listItem.item_name }}</s>
                  <ion-icon v-if="listItem.recipe_id" color="primary" size="small" :icon="restaurant"></ion-icon>
                </h2>
                <h2 v-if="listItem.date_purchased == null" @click="openItemDetailsModal(listItem)">
                  {{ listItem.item_name }}
                  <ion-icon v-if="listItem.recipe_id" color="primary" size="small" :icon="restaurant"></ion-icon>
                </h2>
                <p v-if="listItem.measurement_amount">
                  {{ listItem.measurement_amount }}
                  {{ listItem.measurement.abbreviation }} |
                  {{ listItem.added_user.name }}
                </p>
              </ion-label>
              <ion-checkbox v-if="listItem.date_purchased == null" color="primary" slot="end" @click="markPurchased(listItem.id)"></ion-checkbox>
              <ion-icon v-if="listItem.date_purchased != null" slot="end" :icon="checkmark"></ion-icon>
            </ion-item>
          </ion-list>
        </ion-label>
        <ion-button v-if="listGroup.id == listShown" class="ion-margin-top" @click="openAddItemModal(listGroup.id)">+</ion-button>
      </ion-list>
    </div>
  </master-layout>
</template>

<script>
import {
  IonList,
  IonLabel,
  IonItem,
  IonCheckbox,
  IonButton,
  modalController,
  IonText,
	IonBadge,
  actionSheetController,
  IonIcon,
} from "@ionic/vue";

import { checkmark, restaurant } from "ionicons/icons";
import axios from "axios";
import AddItemModal from "../components/list/AddItemModal.vue";
import ItemDetailsModal from "../components/list/ItemDetailsModal.vue";

export default {
  name: "Lists",
  components: { IonList, IonLabel, IonItem, IonCheckbox, IonButton, IonText, IonBadge, IonIcon},
  data() {
    return {
      listInfo: {},
			listShown: null,
    };
  },
  setup(){
    return {checkmark, restaurant}
  },
  mounted() {
    this.initiateList();
  },
  methods: {
    async initiateList(){
      axios.get("/mylist")
      .then((response) => (this.listInfo = response.data))
      .then(() => this.listShown = localStorage.getItem('group') !== null ? JSON.parse(localStorage.getItem('group')).groupId : this.listInfo[0].id)
      .then(() => this.listInfo.sort(
        function(x,y){ 
          return x.id == JSON.parse(localStorage.getItem('group')).groupId ? -1 : y.id == JSON.parse(localStorage.getItem('group')).groupId ? 1 : 0; 
          }
        ))
      .catch((error) => console.log(error))
    },
    markPurchased(itemId) {
      axios.put("/purchaseditem", {
        listItemId: itemId,
      })
			.then(() => {
				this.initiateList();
			});
    },
    async reversePurchasedActionSheet(itemId){
      const actionSheet = await actionSheetController.create({
        header: 'Toch niet aangekocht?',
        buttons:[
          {
            text:'Klopt!',
            handler: () => {
              this.reversePurchased(itemId)
            }
          },
          {
            text:'Annuleren',
            role:'cancel'
          }
        ]
      })
      await actionSheet.present()
    },
    reversePurchased(itemId){
      axios.put("/reversepurchase", {
        listItemId: itemId,
      })
      .then(() =>{
        this.initiateList();
      })
    },
    async openAddItemModal(groupId) {
      const modal = await modalController.create({
        component: AddItemModal,
        componentProps: {
          groupId: groupId,
        },
      });
      modal.onDidDismiss().then(() => {
        this.initiateList();
      });

      return modal.present();
    },
    async openItemDetailsModal(item) {
      const modal = await modalController.create({
        component: ItemDetailsModal,
        componentProps: {
          itemDetails: item,
        },
      });
      modal.onDidDismiss().then(() => {
        this.initiateList();
      });
      return modal.present();
    },
  },
};
</script>

<style scoped>
ion-checkbox {
  position: relative;
}

ion-badge{
	margin-left:1rem;
}

.container {
  padding: 5%;
}
</style>