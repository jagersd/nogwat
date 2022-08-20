<template>
  <master-layout :pageTitle="$t('shoppingList.title')">
    <ion-item v-if="nogroups" lines="none" id="nogroup">
      {{$t('shoppingList.nogroup')}}
    </ion-item>
    <swiper
    @reachBeginning="currentSlider = 0"
    @slideNextTransitionStart="sliderMovedUp"
    @slidePrevTransitionStart="sliderMovedDown"
    @slideChange="clearStoreFilter"
    >
    <!--GroupID Loop-->
      <swiper-slide v-for="listGroup in listInfo" :key="listGroup.id" >
          <ion-item lines="none">
            <ion-icon v-if="currentSlider != 0" color="primary" slot="start" :icon="chevronBack"></ion-icon>
            <p id="group-name">{{ listGroup.name }}</p>
            <ion-badge color="primary">{{listGroup.active_lists.filter(item => item.user_id_purchased == null).length}}</ion-badge>
            <ion-icon v-if="currentSlider != listInfo.length-1" color="primary" slot="end" :icon="chevronForward"></ion-icon>
          </ion-item>
          <ion-button v-if="listGroup.active_lists.length >= 8" @click="openAddItemModal(listGroup.id, listGroup.stores)">+</ion-button>
          <ion-icon id="filterIcon" v-if="listGroup.stores.length" @click="setStoreFilter(listGroup.active_lists)" :icon="filterCircle" size="large" color="secondary"></ion-icon>
          <ion-radio-group>
            <ion-item v-for="store in storeFilter" :key="store">
              <ion-label color="secondary">{{store}}</ion-label>
              <ion-radio slot="start" :value=store @click="this.selectedStore={storeName:store, groupId:listGroup.id}"></ion-radio>
            </ion-item>
          </ion-radio-group>

          <!--ListItem Loop-->
          <ion-list>
            <ion-reorder-group @ionItemReorder="doReorder($event)" :disabled="false">
              <div v-for="listItem in listGroup.active_lists" :key="listItem.id">
              <ion-item lines="none" v-if="(selectedStore.groupId && selectedStore.groupId == listGroup.id && listItem.store && listItem.store.name == selectedStore.storeName) || !selectedStore.storeName">
                <ion-reorder slot="start"></ion-reorder>
                <ion-label>
                  <h2 v-if="listItem.date_purchased != null">
                    <s @click="reversePurchasedActionSheet(listItem.id)">{{ listItem.item_name }}</s>
                    <ion-icon id="recipe-icon" v-if="listItem.recipe_id" color="primary" size="small" :icon="restaurant" @click="openRecipeQuickViewModal(listItem.group_id,listItem.recipe_id)"></ion-icon>
                  </h2>
                  <h2 v-if="listItem.date_purchased == null">
                    <b @click="openItemDetailsModal(listItem, listGroup.stores)">{{ listItem.item_name }}</b>
                    <ion-icon id="recipe-icon" v-if="listItem.recipe_id" color="primary" size="small" :icon="restaurant" @click="openRecipeQuickViewModal(listItem.group_id,listItem.recipe_id)"></ion-icon>
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
              </div>
            </ion-reorder-group>
          </ion-list>
          <ion-button id="addBtn" @click="openAddItemModal(listGroup.id, listGroup.stores)">+</ion-button>
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
  IonRadio,
  IonRadioGroup,
  IonReorder, 
  IonReorderGroup
} from "@ionic/vue";

import { Swiper, SwiperSlide} from 'swiper/vue';
import 'swiper/swiper.min.css';
import '@ionic/vue/css/ionic-swiper.css';


import { checkmark, restaurant, chevronBack, chevronForward, filterCircle } from "ionicons/icons";
import axios from "axios";
import AddItemModal from "../components/list/AddItemModal.vue";
import ItemDetailsModal from "../components/list/ItemDetailsModal.vue";
import RecipeQuickViewModal from "../components/list/RecipeQuickViewModal.vue";

export default {
  name: "Lists",
  components: { IonList, IonLabel, IonItem, IonCheckbox, IonButton, IonBadge, IonIcon, Swiper, SwiperSlide, IonRadio, IonRadioGroup, IonReorder, IonReorderGroup},
  data() {
    return {
      listInfo: {},
      currentSlider: 0,
      nogroups: false,
      storeFilter: [],
      selectedStore: {}
    };
  },
  setup(){
    const doReorder = (event) => {
      event.detail.complete();
    }
    return {
      checkmark, 
      restaurant,
      chevronBack,
      chevronForward,
      filterCircle,
      doReorder
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
    setStoreFilter(itemList){
      if(this.storeFilter.length){
        this.clearStoreFilter()
      } else {
        let storeArray = [...new Set(itemList.map(item => item.store ? item.store.name : null))]
        this.storeFilter = storeArray.filter(store => store)
      }
    },
    clearStoreFilter(){
      this.storeFilter = []
      this.selectedStore = {}
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
    async openRecipeQuickViewModal(group, recipe){
      const modal = await modalController.create({
        component: RecipeQuickViewModal,
        id:'recipe-quick-view-modal',
        componentProps:{
          groupId: group,
          recipeId: recipe
        }
      })
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
  min-height: 60vh;
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

ion-list{
  padding:0;
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

#filterIcon{
  position: absolute;
  right: 10vw;
}

#recipe-icon{
  padding-left: 5px;
}
</style>