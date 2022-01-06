<template>
<!--GENERAL-->
  <div class="container">
    <h1>{{groupInfo.name}}
      <ion-button v-if="groupInfo.adminCheck.is_admin==1" color="primary" @click="showGroupAdjustForm=true" size="small">
        <ion-icon :icon="pencil"></ion-icon>
      </ion-button>
    </h1>

    <ion-item v-if="showGroupAdjustForm == true">
      <ion-label position="stacked">{{$t('groups.detailsModal.groupName')}}</ion-label>
      <ion-input type="text" required="true" v-model="adjustForm.name" :placeholder="groupInfo.name"></ion-input>
      <ion-label position="stacked">{{$t('misc.description')}}</ion-label>
      <ion-input type="text" required="true" v-model="adjustForm.instructions" :placeholder="groupInfo.admin_instructions"></ion-input>
      <ion-button @click="changeGroupName">{{$t('misc.save')}}</ion-button>
      <ion-button @click="showGroupAdjustForm=false">{{$t('misc.cancel')}}</ion-button>
    </ion-item>

    <i>{{groupInfo.admin_instructions}}</i>
    <!--Default groups-->
    <ion-item lines="none">
      <ion-checkbox v-if="defaultGroupChecker == null || groupInfo.id != defaultGroupChecker.groupId" slot="start" @click="setDefaultGroup" ></ion-checkbox>
      <ion-checkbox v-else disabled="true" checked="true" slot="start"></ion-checkbox>
      <ion-label color="secondary">{{$t('groups.detailsModal.setDefault')}}</ion-label>
    </ion-item>

    <i>{{$t('groups.detailsModal.groupCreated')}} {{formattedDate}} </i>
    <ion-button @click="goToGroupHistory" color="tertiary" expand="block">{{$t('groups.detailsModal.historyBtn')}}</ion-button>
    <!--ParticipantList-->
    <ion-button @click="flipParticipantList">{{$t('groups.detailsModal.participants')}}</ion-button>
    <div class="participantListSection" v-if="showParticipants">
      <ion-list v-for="user in groupInfo.users" :key="user.id">     
        <ion-item>
          <ion-label color="dark">
            <h2>{{user.name}}</h2>
            <p>{{user.email}}</p>
          </ion-label>
          <ion-icon v-if="(groupInfo.adminCheck.is_admin==1 && user.email != $store.state.user.user.email)" 
          :icon="personRemove" 
          size="small" 
          color="danger"
          @click="removeFromGroup(user.id)"
          ></ion-icon>
        </ion-item>
      </ion-list>

      <ion-button v-if="groupInfo.adminCheck.is_admin==1" class="ion-margin-top" @click="formHidden = false">+</ion-button>

      <ion-item v-if="!formHidden">
        <ion-label position="stacked">{{$t('misc.email')}}</ion-label>
        <ion-input inputmode="email" type="email" required="true" v-model="form.invitee" id="inviteForm" placeholder="email@email.com"></ion-input>
        <ion-button @click="sendInvite">{{$t('groups.detailsModal.sendInviteBtn')}}</ion-button>
        <ion-button @click="formHidden = true">{{$t('misc.cancel')}}</ion-button>
      </ion-item>
    
      <!--Invites registered-->
      <h4 v-if="(groupInfo.open_invites_registered.length || groupInfo.open_invites_unregistered.length)">{{$t('groups.detailsModal.invSentTo')}}:</h4>
      <ion-list v-for="invite in groupInfo.open_invites_registered" :key="invite.invitees.id">
        <ion-item>
          <ion-label color="dark">
            <p>{{invite.invitees.email}}</p>
          </ion-label>
        </ion-item>
      </ion-list>
      <!--Invites unregistered-->
      <h4 v-if="groupInfo.open_invites_unregistered.length"></h4>
      <ion-list v-for="invite in groupInfo.open_invites_unregistered" :key="invite.unregistered_email">
        <ion-item>
          <ion-label color="dark">
            <p>{{invite.unregistered_email}}</p>
          </ion-label>
        </ion-item>
      </ion-list>
    </div>

    <!--Stores-->
    <ion-button @click="flipStoreList">{{$t('groups.detailsModal.storeType')}}</ion-button>
    <div class="stores-section" v-if="showStores">
      <ion-list v-for="store in groupInfo.stores" :key="store.id">
        <ion-item>
          <ion-label color="dark">
            <h2>{{store.name}}</h2>
            <p>{{store.description}}</p>
          </ion-label>
          <ion-icon v-if="(groupInfo.adminCheck.is_admin==1)" 
          :icon="pencil" 
          size="small" 
          color="primary"
          slot="end"
          @click="storeAdjustId = store.id"
          ></ion-icon>
        </ion-item>
        <!--store adjust form -->
        <ion-item v-if="storeAdjustId == store.id">
          <ion-label position="stacked">{{$t('groups.detailsModal.storeName')}}</ion-label>
          <ion-input type="text" :placeholder="store.name" v-model="storeAdjustFrom.name"></ion-input>
          <ion-label position="stacked">{{$t('misc.description')}}</ion-label>
          <ion-input type="text" :placeholder="store.description" v-model="storeAdjustFrom.description"></ion-input>
          <ion-button @click="adjustStoreDetails(store.id)">{{$t('misc.save')}}</ion-button>
          <ion-button @click="storeAdjustId = null">{{$t('misc.cancel')}}</ion-button>
        </ion-item>
      </ion-list>
      <p v-if="groupInfo.adminCheck.is_admin!=1">{{$t('groups.detailsModal.adminExp')}}</p>
      <ion-button v-if="groupInfo.adminCheck.is_admin==1" class="ion-margin-top" @click="storeFormHidden = false">+</ion-button>
      <ion-item v-if="!storeFormHidden">
        <ion-label position="stacked">{{$t('groups.detailsModal.storeName')}}</ion-label>
        <ion-input type="text" required="true" v-model="storeForm.name" placeholder="Voorbeeld: Buurtboer"></ion-input>
        <ion-label position="stacked">{{$t('misc.description')}}</ion-label>
        <ion-input type="text" required="true" v-model="storeForm.description" placeholder="Voorbeeld: supermarkt"></ion-input>
        <ion-button @click="addStore">{{$t('misc.add')}}</ion-button>
        <ion-button @click="storeFormHidden = true">{{$t('misc.cancel')}}</ion-button>
      </ion-item>
    </div>
  </div>
  <div class="slot-menu">  
    <ion-button @click="closeModal" expand="block">{{$t('misc.close')}}</ion-button>
    <ion-button v-if="groupInfo.adminCheck.is_admin==0" @click="leaveGroup" color="danger" expand="block">{{$t('groups.detailsModal.leaveGroupBtn')}}</ion-button>
    <ion-button v-if="groupInfo.adminCheck.is_admin==1" @click="disbandGroup" color="danger" expand="block">{{$t('groups.detailsModal.removeGroupBtn')}}</ion-button>
  </div>
</template>

<script>
import axios from 'axios'
import {
 IonButton, modalController, IonList, IonLabel, IonItem, IonInput, IonCheckbox, toastController, actionSheetController, IonIcon
} from "@ionic/vue";
import { pencil, personRemove } from "ionicons/icons";
import moment from 'moment'

import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'GroupDetailModal',
    components: {
    IonButton, IonList, IonLabel, IonItem, IonInput, IonCheckbox, IonIcon
  },
  props: ['groupId'],

  async created() {
    axios.get('/groupdetails/'+this.groupId)
    .then(response => (this.groupInfo = response.data))
    .catch(error => console.log(error))
  },
  
  data() {
    return {
      defaultGroupChecker: JSON.parse(localStorage.getItem('group')),
      formHidden: true,
      storeFormHidden: true,
      showParticipants: true,
      showStores: false,
      showGroupAdjustForm: false,
      groupInfo: {
        name:'',
        created_at: null,
        adminCheck:{
          is_admin:null
        },
        users:[],
        open_invites_registered:[],
        open_invites_unregistered:[]
      },
        adjustForm:{
        groupId: this.groupId,
        name:'',
        instructions: ''
      },
      form: {
        invitee:"",
        groupId:this.groupId,
      },
      storeForm:{
        name:"",
        groupId:this.groupId,
        description:"",
      },
      storeAdjustId: null,
      storeAdjustFrom:{
        groupId:this.groupId,
        storeId: null,
        name:"",
        description:"",
      }

    }
  },
  setup() {
    const closeModal = () => {
    modalController.dismiss();
    }
    return { closeModal, pencil, personRemove }
  },
  computed:{
    formattedDate: function(){
      return this.groupInfo.created_at ? moment(this.groupInfo.created_at).format('D MMM YYYY') : null
    }
  },
  methods: {
    sendInvite(){
      axios.post('/inviteuser', this.form)
      .then(this.toastResponse)
      .then(this.closeModal)
      .catch(error => {
        this.errorMessage = error.message;
        console.error('there was en error!', error)
      })
    },
    async toastResponse(){
      const toast = await toastController
      .create({
        message:'Uitnodiging verzonden!',
        color: 'dark',
        position: 'top',
        duration: 2000
      })
      return toast.present()
    },
    addStore(){
      axios.post('/addstore', this.storeForm)
      .then(this.closeModal)
      .catch(error=> {
        this.errorMessage = error.message;
        console.error('there was an error!', error)
      })
    },
    setDefaultGroup(){
      this.$store.commit('setGroupData', {groupId: this.groupId, groupName:this.groupInfo.name})
    },
    goToGroupHistory(){
      this.$router.push({name: 'grouphistory', params: {groupId : this.groupId} })
      .then(this.closeModal)
    },
    flipParticipantList(){
      this.showParticipants == true ? this.showParticipants = false : this.showParticipants = true
      this.showStores = false
    },
    flipStoreList(){
      this.showStores == false ? this.showStores = true : this.showStores = false
      this.showParticipants = false
    },
    async leaveGroup(){
      const leaveActionSheet = await actionSheetController
      .create({
        header:'Wil je echt de groep verlaten?',
        buttons: [
          {
            text: 'Ja',
            handler: ()=> {
              axios.post('/leavegroup',{groupId:this.groupId})
              .then(this.closeModal)
              .catch(error=>{
                this.errorMessage = error.message
                console.error('there was an error!', error)
              })
            }
          },
          {
            text: 'Nee',
            role: 'cancel'
          }
        ]
      })
      await leaveActionSheet.present()
    },
    async disbandGroup(){
      const disbandActionSheet = await actionSheetController
      .create({
        header:'Weet je zeker dat je deze groep wilt verwijderen?',
        buttons: [
          {
            text: 'Ja',
            handler: ()=> {
              axios.post('/disbandgroup',{groupId:this.groupId})
              .then(this.closeModal)
              .catch(error=>{
                this.errorMessage = error.message
                console.error('there was an error!', error)
              })
            }
          },
          {
            text: 'Nee',
            role: 'cancel'
          }
        ]
      })
      await disbandActionSheet.present()
    },
    async removeFromGroup(userId){
      const removeFromGroupActionSheet = await actionSheetController
      .create({
        header:'Weet je het zeker?',
        buttons:[
          {
            text: 'Ja',
            handler: () => {
              axios.post('/kickfromgroup',{groupId:this.groupId, removeUser:userId})
              .then(this.closeModal)
              .catch(error=>{
                this.errorMessage = error.message
                console.error('there was een error!', error)
              })
            },
          },
          {
            text: 'Nee',
            role: 'cancel'
          }
        ]
      })
      await removeFromGroupActionSheet.present()
    },
    async changeGroupName(){
      axios.post('/changegroupname',this.adjustForm)
      .then(this.closeModal)
      .catch(error=> {
        this.errorMessage = error.message;
        console.error('there was an error!', error)
      })
    },
    async adjustStoreDetails(storeId){
      this.storeAdjustFrom.storeId = storeId
      axios.post('/adjuststorename',this.storeAdjustFrom)
      .catch(error=> {
        this.errorMessage = error.message;
        console.error('there was an error!', error)
      })
      .then(this.closeModal)
    }
  }
});
</script>

<style scoped>
h1, h4, p, i{
  color: black;
}

.container{
  padding-left: 1rem;
  padding-right: 1rem;
  overflow-x: scroll;
}
</style>