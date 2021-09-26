<template>
  <master-layout pageTitle="Groepen">
    <br>
    <ion-text class="ion-margin-top">Hi {{userInfo.name}}!</ion-text><br>
    <ion-text class="ion-margin-top">Dit is een overzicht van je groepen. Tab om details te bekijken of aanpassingen te maken.</ion-text>
    <ion-list v-for="group in userInfo.groups" :key="group.id">
      <ion-card color="primary" @click="openGroupDetailModal(group.id)">
        <ion-card-header><ion-card-title>{{group.name}}</ion-card-title></ion-card-header>
        <ion-card-content>{{group.admin_instructions}}</ion-card-content>
      </ion-card>
    </ion-list>

    <ion-button expand="block" @click="openCreateGroupModal">Maak een nieuwe groep aan</ion-button>
  </master-layout>
</template>



<script>
import { IonButton, modalController, IonText, IonList, IonCard, IonCardHeader, IonCardContent, IonCardTitle } from '@ionic/vue'
import MasterLayout from '@/components/MasterLayout.vue'
import axios from 'axios'
import CreateGroupModal from '@/components/groups/CreatGroupModal.vue'
import GroupDetailModalVue from '@/components/groups/GroupDetailModal.vue'

export default {
  name: 'Groups',
  components: {IonButton, IonText, IonList, IonCard, IonCardHeader, MasterLayout, IonCardContent, IonCardTitle},
  data() {
    return {
      userInfo: {},
      defaultGroupId: this.$store.state.groupId
    }
  },
  created() {
    axios.get('/me')
    .then(response => (this.userInfo = response.data))
    .catch(error => console.log(error))
  },
  methods: {
    async openCreateGroupModal() {
      const modal = await modalController.create({
        component: CreateGroupModal,
      })
      return modal.present();
    },

    async openGroupDetailModal(id) {
      const modal = await modalController.create({
        component: GroupDetailModalVue,
        componentProps: {
          groupId: id
        }
      })
      return modal.present();
    },



  }
}
</script>