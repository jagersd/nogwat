<template>
  <master-layout :pageTitle="$t('groups.groups')">
    <div class="container">
      <ion-text class="ion-margin-top">{{$t('groups.subtitle1', {user: $store.state.user.user.name} )}}</ion-text><br>
      <ion-text class="ion-margin-top">{{$t('groups.subtitle2')}}</ion-text>

      <!--Grouplists-->
      <ion-text><h4>{{$t('groups.groups')}}:</h4></ion-text>
      <ion-list v-for="group in getData.groups" :key="group.id">
        <ion-card color="primary" @click="openGroupDetailModal(group.id)">
          <ion-card-header><ion-card-title>{{group.name}}</ion-card-title></ion-card-header>
          <ion-card-content>{{group.admin_instructions}}</ion-card-content>
        </ion-card>
      </ion-list>

      <!--Invites-->
      <ion-text v-if="getData.invites.length"><h4>{{$t('groups.invites')}}:</h4></ion-text>
      <ion-list v-for="invite in getData.invites" :key="invite.id">
        <ion-card color="secondary" @click="invitationActionSheet(invite.id, invite.group.id, invite.group.name)">
          <ion-card-header><ion-card-title>{{invite.group.name}}</ion-card-title></ion-card-header>
          <ion-card-content>{{$t('groups.sentBy')}}: {{invite.invitor.name}}</ion-card-content>
        </ion-card>

      </ion-list>
    </div>
    <ion-button color="tertiary" expand="block" @click="openCreateGroupModal">{{$t('groups.createGroupBtn')}}</ion-button>
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
  ionViewWillEnter() {
    this.getGroups()
  },
  methods: {
    getGroups(){
      axios.get('/mygroups')
      .then(response => (this.getData = response.data))
      .catch(error => console.log(error))
    },
    async openCreateGroupModal() {
      const modal = await modalController.create({
        component: CreateGroupModal,
      })
      modal.onDidDismiss().then(() => {
        this.getGroups();
      });
      return modal.present();
    },
    async openGroupDetailModal(id) {
      const modal = await modalController.create({
        component: GroupDetailModalVue,
        componentProps: {
          groupId: id
        }
      })
      modal.onDidDismiss().then(() => {
        this.getGroups();
      });
      return modal.present();
    },
    setDefaultGroup(invitedGroupId,invitedGroupName){
      if(!this.defaultGroupId){
        this.$store.commit('setGroupData', {groupId: invitedGroupId, groupName:invitedGroupName})
      }
    },
    async invitationActionSheet(id, invitedGroupId, invitedGroupName){
      const inviteActionSheet = await actionSheetController
      .create({
        header:'Accepteren?',
        buttons: [
          {
            text: 'Ja',
            handler: () => {
              axios.post('/acceptinvite', {inviteId:id})
              .then(this.$router.push({name: 'lists'}))
              .then(this.setDefaultGroup(invitedGroupId, invitedGroupName))
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