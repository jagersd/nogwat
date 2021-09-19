<template>
  <master-layout pageTitle="Dashboard">
    <br>
    <ion-text class="ion-margin-top">Hi {{userInfo.name}}!</ion-text>
    <br>
    <ion-text class="ion-margin-top">Selecteer een standaard group</ion-text>
    <br>
    <ion-text class="ion-margin-top">default group id {{defaultGroupId}}</ion-text>
    <ion-button v-if="!userInfo.groups" class="ion-margin-top" expand="block" @click="openCreateGroupModal">Create Group</ion-button>
    <ul v-for="group in userInfo.groups" :key="group.id">
      <li><ion-button @click="setGroupid(group.id)">{{group.name}}</ion-button></li>
    </ul>
  </master-layout>
</template>



<script>
import { IonButton, modalController, IonText } from '@ionic/vue'
import MasterLayout from '@/components/MasterLayout.vue'
import axios from 'axios'
import CreateGroupModal from '@/components/groups/CreatGroupModal.vue'

export default {
  name: 'Dashboard',
  components: {IonButton, IonText, MasterLayout},
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