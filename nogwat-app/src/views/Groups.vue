<template>
  <master-layout pageTitle="Groepen">
    <div class="container">
      <ion-text class="ion-margin-top">Hi {{$store.state.user.user.name}}!</ion-text><br>
      <ion-text class="ion-margin-top">Dit is een overzicht van je groepen. Tab om details te bekijken of aanpassingen te maken.</ion-text>

      <!--Grouplists-->
      <ion-text><h4>Groepen:</h4></ion-text>
      <ion-list v-for="group in getData.groups" :key="group.id">
        <ion-card color="primary" @click="openGroupDetailModal(group.id)">
          <ion-card-header><ion-card-title>{{group.name}}</ion-card-title></ion-card-header>
          <ion-card-content>{{group.admin_instructions}}</ion-card-content>
        </ion-card>
      </ion-list>

      <!--Invites-->
      <ion-text v-if="getData.invites.length"><h4>Uitnodigingen:</h4></ion-text>
      <ion-list v-for="invite in getData.invites" :key="invite.id">
        <ion-card color="secondary" @click="invitationActionSheet(invite.id)">
          <ion-card-header><ion-card-title>{{invite.group.name}}</ion-card-title></ion-card-header>
          <ion-card-content>verzonden door: {{invite.invitor.name}}</ion-card-content>
        </ion-card>

      </ion-list>
    </div>
    <ion-button expand="block" @click="openCreateGroupModal">Maak een nieuwe groep aan</ion-button>
    {{$store.state.groupId}}
  </master-layout>
</template>



<script>
import { IonButton, modalController, IonText, IonList, IonCard, IonCardHeader, IonCardContent, IonCardTitle, actionSheetController } from '@ionic/vue'
import MasterLayout from '@/components/MasterLayout.vue'
import axios from 'axios'
import CreateGroupModal from '@/components/groups/CreateGroupModal.vue'
import GroupDetailModalVue from '@/components/groups/GroupDetailModal.vue'

export default {
  name: 'Groups',
  components: {IonButton, IonText, IonList, IonCard, IonCardHeader, MasterLayout, IonCardContent, IonCardTitle},
  data() {
    return {
      getData: {
        groups:[],
        invites:[],
      },
      defaultGroupId: this.$store.state.groupId
    }
  },
  async created() {
    axios.get('/mygroups')
    .then(response => (this.getData = response.data))
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
    async invitationActionSheet(id){
      const inviteActionSheet = await actionSheetController
      .create({
        header:'Accepteren?',
        buttons: [
          {
            text: 'Ja',
            handler: () => {
              axios.post('/acceptinvite', {inviteId:id})
              .then(this.$router.push({name: 'lists'}))
              .catch(error=> {
                this.errorMessage = error.message;
                console.error('there was an error!', error)
              })
            }
          },
          {
            text: 'Nee',
            handler: () => {
              axios.post('/rejectinvite', {inviteId:id})
              .then(this.$router.push({name: 'Home'}))
              .catch(error=> {
                this.errorMessage = error.message;
                console.error('there was an error!', error)
              })
            }
          },
          {
            text: 'Nog niet',
            role: 'cancel'
          }
        ]
      })
      await inviteActionSheet.present()
    },
  }
}
</script>

<style scoped>
.container{
  padding: 5%;
}
</style>