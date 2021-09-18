<template>
  <master-layout pageTitle="About page">
    <p>Hi {{userInfo.name}}!</p>
    {{userInfo.groups}}
    <ion-button class="ion-margin-top" expand="block" @click="openCreateGroupModal">Create Group</ion-button>
    <ion-content v-for="group in userInfo.groups" :key="group.id">
      <ion-button>{{group.name}}</ion-button>
    </ion-content>
  </master-layout>
</template>



<script>
import { IonButton, modalController, IonContent } from '@ionic/vue'
import MasterLayout from '@/components/MasterLayout.vue'
import axios from 'axios'
import CreateGroupModal from '@/components/groups/CreatGroupModal.vue'

export default {
  name: 'Dashboard',
  components: {IonButton, MasterLayout, IonContent},
  data() {
    return {
      userInfo: {}
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
    }
  }
}
</script>