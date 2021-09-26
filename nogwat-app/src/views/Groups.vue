<template>
  <master-layout pageTitle="Groepen">
    <ion-list></ion-list>
    <br>
    <ion-text class="ion-margin-top">Hi {{userInfo.name}}!</ion-text>
    <br>
    <ion-text class="ion-margin-top">Selecteer een standaard group</ion-text>
    <br>
    <ion-text class="ion-margin-top">default group id {{defaultGroupId}}</ion-text>
    
    <ion-list v-for="group in userInfo.groups" :key="group.id">
      <ion-card @click="setGroupid(group.id)">
        <ion-card-header>{{group.name}}</ion-card-header>
      </ion-card>
    </ion-list>

    <ion-button expand="block" @click="openCreateGroupModal">Create Group</ion-button>
  </master-layout>
</template>



<script>
import { IonButton, modalController, IonText, IonList, IonCard,IonCardHeader } from '@ionic/vue'
import MasterLayout from '@/components/MasterLayout.vue'
import axios from 'axios'
import CreateGroupModal from '@/components/groups/CreatGroupModal.vue'

export default {
  name: 'Groups',
  components: {IonButton, IonText, IonList, IonCard, IonCardHeader, MasterLayout},
  data() {
    return {
      userInfo: {},
      defaultGroupId: this.$store.state.groupId
    }
  },
  mounted() {
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
    setGroupid(groupId) {
      this.$store.commit('setDefaultGroup',groupId)
      console.log(this.$store.state.groupId)
    }


  }
}
</script>