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
      <ion-input type="text" required="true" v-model="adjustForm.name" :placeholder="groupInfo.name" maxlength="30"></ion-input>
      <ion-label position="stacked">{{$t('misc.description')}}</ion-label>
      <ion-input type="text" required="true" v-model="adjustForm.instructions" :placeholder="groupInfo.admin_instructions" maxlength="150"></ion-input>
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

    <!--Menu buttons-->
    <ion-item lines="none">
      <ion-button size="larger" @click="flipParticipantList">{{$t('groups.detailsModal.participants')}}</ion-button>
      <ion-button size="larger" @click="flipStoreList">{{$t('groups.detailsModal.storeType')}}</ion-button>
    </ion-item>

    <!--ParticipantList-->
    <div class="participantListSection" v-if="showParticipants">
      <ion-list v-for="user in groupInfo.users" :key="user.id">     
        <ion-item>
          <ion-label color="dark" v-if="groupAdmins.includes(user.id)">
            <h2>{{user.name}} <ion-icon :icon="shield" color="primary"></ion-icon></h2>
            <p>{{user.email}}</p>
          </ion-label>
          <ion-label color="dark" v-else>
            <h2>{{user.name}} <ion-icon 
            v-if="groupInfo.adminCheck.is_admin==1" 
            :icon="shieldCheckmark" 
            color="secondary"
            @click="promoteToAdmin(user.id, user.name)"
            ></ion-icon>
            </h2>
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
    <div class="stores-section" v-if="showStores">
      <ion-button v-if="groupInfo.adminCheck.is_admin==1" class="ion-margin-top" @click="storeFormHidden = false" slot="end">+</ion-button>
      <ion-item v-if="!storeFormHidden">
        <ion-label position="stacked">{{$t('groups.detailsModal.storeName')}}</ion-label>
        <ion-input type="text" required="true" v-model="storeForm.name" placeholder="Voorbeeld: Buurtboer" maxlength="30"></ion-input>
        <ion-label position="stacked">{{$t('misc.description')}}</ion-label>
        <ion-input type="text" required="true" v-model="storeForm.description" placeholder="Voorbeeld: supermarkt" maxlength="150"></ion-input>
        <ion-button @click="addStore">{{$t('misc.add')}}</ion-button>
        <ion-button @click="storeFormHidden = true">{{$t('misc.cancel')}}</ion-button>
      </ion-item>
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
          <ion-input type="text" :placeholder="store.name" v-model="storeAdjustFrom.name" maxlength="30"></ion-input>
          <ion-label position="stacked">{{$t('misc.description')}}</ion-label>
          <ion-input type="text" :placeholder="store.description" v-model="storeAdjustFrom.description" maxlength="150"></ion-input>
          <ion-button @click="adjustStoreDetails(store.id)">{{$t('misc.save')}}</ion-button>
          <ion-button @click="adjustStoreDetails(store.id, true)" color="danger">{{$t('misc.delete')}}</ion-button>
          <ion-button @click="storeAdjustId = null">{{$t('misc.cancel')}}</ion-button>
        </ion-item>
      </ion-list>
      <p v-if="groupInfo.adminCheck.is_admin!=1">{{$t('groups.detailsModal.adminExp')}}</p>
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
import { pencil, personRemove, shield, shieldCheckmark } from "ionicons/icons";
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
    .then(response => (this.setGroupAdmins(response.admins)))
    .catch(error => console.log(error))
  },
  
  data() {
    return {
      defaultGroupChecker: JSON.parse(localStorage.getItem('group')),
      formHidden: true,
      storeFormHidden: true,
      showParticipants: false,
      showStores: false,
      showGroupAdjustForm: false,
      groupAdmins:[],
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
    return { closeModal, pencil, personRemove, shield, shieldCheckmark }
  },
  computed:{
    formattedDate: function(){
      return this.groupInfo.created_at ? moment(this.groupInfo.created_at).format('D MMM YYYY') : null
    }
  },
  methods: {
    setGroupAdmins(apiResponse){
      apiResponse.forEach(element=>this.groupAdmins.push(element.user_id))
    },
    async sendInvite(){
      if(this.form.invitee != ""){
        axios.post('/inviteuser', this.form)
        .then(this.toastResponse)
        .then(this.closeModal)
        .catch(error => {
          this.errorMessage = error.message;
          console.error('there was en error!', error)
        })
      } else {
        const toast = await toastController
        .create({
          message:'Email adres verplicht',
          color: 'danger',
          position: 'top',
          duration: 2000
        })
        return toast.present()
      }
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
    async addStore(){
      if(this.storeForm.name == "" || this.storeForm.description == ""){
        const toast = await toastController
        .create({
          message:'Naam en omschrijving zijn verplicht',
          color: 'danger',
          position: 'middle',
          duration: 2000
        })
        return toast.present()
      }else{
        axios.post('/addstore', this.storeForm)
        .then(this.closeModal)
        .catch(error=> {
          this.errorMessage = error.message;
          console.error('there was an error!', error)
        })
        const toast = await toastController
        .create({
          message:'Nieuwe winkel toegevoegd',
          color: 'primary',
          position: 'middle',
          duration: 2000
        })
        return toast.present()
      }
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
        header:'Persoon uit de groep verwijderen?',
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
    async promoteToAdmin(userId, userName){
      const promoteActiveSheet = await actionSheetController
      .create({
        header:'Maak ' + userName +  ' groep admin?',
        buttons:[
          {
            text: 'Ja',
            handler: () => {
              axios.post('/promoteuser',{groupId:this.groupId, promoteUser:userId})
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
      await promoteActiveSheet.present()
    },
    async changeGroupName(){
      axios.post('/changegroupname',this.adjustForm)
      .then(this.closeModal)
      .catch(error=> {
        this.errorMessage = error.message;
        console.error('there was an error!', error)
      })
    },
    async adjustStoreDetails(storeId, deleteAction=false){
      this.storeAdjustFrom.storeId = storeId
      if(deleteAction == true){
        const deleteStoreActionSheet = await actionSheetController
        .create({
          header: this.$t('groups.stores.deleteConfirm'),
          buttons:[
            {
              text: this.$t('misc.yes'),
              handler: () => {
              axios.put('/deletestore',{
              groupId:this.groupId,
              storeId:this.storeAdjustFrom.storeId
              })
              .catch(error=> {
                this.errorMessage = error.message;
                console.error('there was an error!', error)
              })
              .then(this.closeModal)
              }
            },
            {
              text: this.$t('misc.no'),
              role: 'cancel'
            }
          ]
        })
        await deleteStoreActionSheet.present()
      }else{
        axios.post('/adjuststorename',this.storeAdjustFrom)
        .catch(error=> {
          this.errorMessage = error.message;
          console.error('there was an error!', error)
        })
        .then(this.closeModal)
      }
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