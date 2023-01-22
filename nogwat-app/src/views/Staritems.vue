<template>
  <master-layout pageTitle="Vaak op het lijstje">
    <div class="container">
      <!--Group Loop-->
      <ion-list v-for="group in starData" :key="group.group_id">
        <ion-label color="primary">
          {{group.name}}
        </ion-label>
        <!--Item Loop-->
        <ion-list v-for="item in group.items" :key="item.item_name">
          <ion-item detail="true" @click="openAddFromStarItemModal(group.group_id,group.stores,item.item_name)">
            {{item.item_name}} ( {{item.name_counter}}x)
          </ion-item>

        </ion-list>
      </ion-list>
    </div>
  </master-layout>
</template>

<script>
import axios from 'axios'
import { IonList, IonItem, IonLabel, modalController} from '@ionic/vue'
import AddFromStarItemModal from '../components/list/AddFromStarItemModal.vue'

export default {
  name: "StarItems",
  components: {IonList, IonItem, IonLabel},

  ionViewWillEnter(){
    this.initiateGetItems()
  },
  data(){
    return{
      starData: {}
    }
  },
  methods: {
    async initiateGetItems(){
      axios.get("/staritems")
      .then(response => (this.starData = response.data))
      .catch(error => console.log(error))
    },
    async openAddFromStarItemModal(groupId, stores, item){
      const modal = await modalController.create({
        component: AddFromStarItemModal,
        componentProps: {
          listItem: item,
          groupId: groupId,
          stores: stores,
        },
      })
      return modal.present()
    }
  }
}
</script>

<style scoped>
.container{
  padding-left: 10px;
}

</style>
