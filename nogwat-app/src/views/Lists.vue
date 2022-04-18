<template>
  <master-layout :pageTitle="$t('shoppingList.title')">
    <ion-item v-if="nogroups" lines="none" id="nogroup">
      {{$t('shoppingList.nogroup')}}
    </ion-item>
    <swiper
    @reachBeginning="currentSlider = 0"
    @slideNextTransitionStart="sliderMovedUp"
    @slidePrevTransitionStart="sliderMovedDown"
    >
    <!--GroupID Loop-->
      <swiper-slide v-for="listGroup in listInfo" :key="listGroup.id" >
          <ion-item lines="none">
            <ion-icon v-if="currentSlider != 0" color="primary" slot="start" :icon="chevronBack"></ion-icon>
            <p id="group-name">{{ listGroup.name }}</p>
            <ion-badge color="danger">{{listGroup.active_lists.filter(item => item.user_id_purchased == null).length}}</ion-badge>
            <ion-icon v-if="currentSlider != listInfo.length-1" color="primary" slot="end" :icon="chevronForward"></ion-icon>
          </ion-item>
          <!--ListItem Loop-->
          <ion-list v-for="listItem in listGroup.active_lists" :key="listItem.id">
            <ion-item>
              <ion-label>
                <h2 v-if="listItem.date_purchased != null" @click="reversePurchasedActionSheet(listItem.id)">
                  <s>{{ listItem.item_name }}</s>
                  <ion-icon v-if="listItem.recipe_id" color="primary" size="small" :icon="restaurant"></ion-icon>
                </h2>
                <h2 v-if="listItem.date_purchased == null" @click="openItemDetailsModal(listItem, listGroup.stores)">
                  {{ listItem.item_name }}
                  <ion-icon v-if="listItem.recipe_id" color="primary" size="small" :icon="restaurant"></ion-icon>
                </h2>
                <p v-if="(listItem.measurement_amount!=null)">
                  {{ listItem.measurement_amount }}
                  {{ listItem.measurement.abbreviation }} |
                  <i v-if="listItem.store">
                  {{listItem.store.name}}
                  </i>
                </p>
                <p v-if="(listItem.measurement_amount ==null && listItem.store != null)">{{listItem.store.name}}</p>
                
              </ion-label>
              <ion-checkbox v-if="listItem.date_purchased == null" color="primary" slot="end" @click="markPurchased(listItem.id)"></ion-checkbox>
              <ion-icon v-if="listItem.date_purchased != null" slot="end" :icon="checkmark"></ion-icon>
            </ion-item>
          </ion-list>
          <ion-button class="add-btn" @click="openAddItemModal(listGroup.id, listGroup.stores)">+</ion-button>
      </swiper-slide>
    </swiper>
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
	IonBadge,
  actionSheetController,
  IonIcon,
} from "@ionic/vue";

import { Swiper, SwiperSlide} from 'swiper/vue';
import 'swiper/swiper.min.css';
import '@ionic/vue/css/ionic-swiper.css';


import { checkmark, restaurant, chevronBack, chevronForward } from "ionicons/icons";
import axios from "axios";
import AddItemModal from "../components/list/AddItemModal.vue";
import ItemDetailsModal from "../components/list/ItemDetailsModal.vue";

export default {
  name: "Lists",
  components: { IonList, IonLabel, IonItem, IonCheckbox, IonButton, IonBadge, IonIcon, Swiper, SwiperSlide},
  data() {
    return {
      listInfo: {},
      currentSlider: 0,
      nogroups: false
    };
  },
  setup(){
    return {
      checkmark, 
      restaurant,
      chevronBack,
      chevronForward,
    }
  },
  ionViewWillEnter() {
    this.initiateList();
  },
  methods: {
    async initiateList(){
      axios.get("/mylist")
      .then((response) => (this.listInfo = response.data)(this.nogroups = response.status == 204 ? true : false))
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
    async openAddItemModal(groupId, stores) {
      const modal = await modalController.create({
        component: AddItemModal,
        id:'add-item-modal',
        componentProps: {
          groupId: groupId,
          stores: stores,
        },
      });
      modal.onDidDismiss().then(() => {
        this.initiateList();
      });

      return modal.present();
    },
    async openItemDetailsModal(item, stores) {
      if(item.measurement == null){
        item.measurement = {
          abbreviation: null
        }
      }
      const modal = await modalController.create({
        component: ItemDetailsModal,
        id:'item-detail-modal',
        componentProps: {
          itemDetails: item,
          stores: stores,
        },
      });
      modal.onDidDismiss().then(() => {
        this.initiateList();
      });
      return modal.present();
    },
    sliderMovedUp(){
      this.currentSlider+=1
    },
    sliderMovedDown(){
      if(this.currentSlider !== 0){
      this.currentSlider-=1
      }
    }
  },
};
</script>

<style scoped>

.swiper-slide{
  margin-top: 3vh;
  min-height: 80vh;
}

#group-name{
  font-size: large;
  margin-left: 2rem;
  color: var(--ion-color-primary);
  font-weight: bold;
}

#nogroup{
  margin-top: 40vh;
}

ion-checkbox {
  position: relative;
}

ion-button {
  margin-left: 3vw;
}

ion-badge{
	margin-left:1rem;
}
</style>