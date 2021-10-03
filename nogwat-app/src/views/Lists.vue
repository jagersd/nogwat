<template>
  <master-layout pageTitle="Boodschappenlijst">
    <div class="container">
    <div v-if="!listInfo.length">
        <ion-text>Cooking with fire! Je boodschappenlijst is leeg :)</ion-text>
    </div>
    <ion-list id="main-content" v-for="listGroup in listInfo" :key="listGroup.id">
        <ion-label>
            <h1>{{listGroup.name}}</h1>
            <ion-list v-for="listItem in listGroup.active_lists" :key="listItem.id">
                <ion-item>
                <ion-label>
                    <h2 @click="openItemDetailsModal(listItem)">{{listItem.item_name}}</h2>
                    <p v-if="listItem.measurement_amount">{{listItem.measurement_amount}} {{listItem.measurement.abbreviation}} | {{listItem.added_user.name}}</p>
                </ion-label>
                <ion-checkbox v-if="listItem.date_purchased != null" checked disabled color="primary" slot="end"></ion-checkbox>
                <ion-checkbox v-if="listItem.date_purchased == null" color="primary" slot="end" @click="markPurchased(listItem.id)"></ion-checkbox>
                </ion-item>
            </ion-list>
        </ion-label>
        <ion-button class="ion-margin-top" @click="openAddItemModal(listGroup.id)">+</ion-button>
    </ion-list>
    </div>
  </master-layout>
</template>

<script>
import {IonList, IonLabel, IonItem, IonCheckbox, IonButton, modalController, IonText} from '@ionic/vue'
import axios from 'axios'
import AddItemModal from '../components/list/AddItemModal.vue'
import ItemDetailsModal from '../components/list/ItemDetailsModal.vue'

export default {
    name: 'Lists',
    components: {IonList, IonLabel,IonItem, IonCheckbox, IonButton, IonText},
    data() {
        return {
            listInfo: {},
        }
    },
    async created() {
        axios.get('/mylist')
        .then(response => (this.listInfo = response.data))
        .catch(error => console.log(error))
    },
    methods: {
        markPurchased(itemId){
            axios.put('/purchaseditem',{
                listItemId: itemId
            })
            .then(() => {
            axios.get('/mylist')
            .then(response => (this.listInfo = response.data))
            .catch(error => console.log(error))
            })
        },
        async openAddItemModal(groupId) {
            const modal = await modalController.create({
                component:AddItemModal,
                componentProps: {
                    groupId:groupId
                }
            })
            modal.onDidDismiss().then(() => {
            axios.get('/mylist')
            .then(response => (this.listInfo = response.data))
            .catch(error => console.log(error))
            })

            return modal.present()
        },
        async openItemDetailsModal(item) {
            const modal = await modalController.create({
                component:ItemDetailsModal,
                componentProps: {
                    itemDetails:item
                }
            })
            modal.onDidDismiss().then(() => {
            axios.get('/mylist')
            .then(response => (this.listInfo = response.data))
            .catch(error => console.log(error))
            })
            return modal.present()
        }
    },
}
</script>

<style scoped>
ion-checkbox {
   position:relative;
}
.container{
    padding: 5%;
}
</style>