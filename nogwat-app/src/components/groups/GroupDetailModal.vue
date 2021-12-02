<template>
<!--GENERAL-->
  <div class="container">
    {{groupInfo.open_invites}}
    <h1>{{groupInfo.name}}</h1>
    <i>{{groupInfo.admin_instructions}}</i>
    <!--Default groups-->
    <ion-item lines="none">
      <ion-checkbox v-if="defaultGroupChecker == null || groupInfo.id != defaultGroupChecker.groupId" slot="start" @click="setDefaultGroup" ></ion-checkbox>
      <ion-checkbox v-else disabled="true" checked="true" slot="start"></ion-checkbox>
      <ion-label color="secondary">Instellen standaard groep</ion-label>
    </ion-item>

    <p>Groep aangemaakt op:
    <ion-datetime display-format="DD-MM-YYYY" :value="groupInfo.created_at"></ion-datetime>
    </p>
    <!--ParticipantList-->
    <ion-button @click="flipParticipantList">Deelnemers</ion-button>
    <div class="participantListSection" v-if="showParticipants">
      <ion-list v-for="user in groupInfo.users" :key="user.id">     
        <ion-item>
          <ion-label color="dark">
            <h2>{{user.name}}</h2>
            <p>{{user.email}}</p>
          </ion-label>
        </ion-item>
      </ion-list>

      <ion-button v-if="groupInfo.adminCheck.is_admin==1" class="ion-margin-top" @click="formHidden = false">+</ion-button>

      <ion-item v-if="!formHidden">
        <ion-input inputmode="email" type="email" required="true" v-model="form.invitee" id="inviteForm" placeholder="email"></ion-input>
        <ion-button @click="sendInvite">Verstuur uitnodiging</ion-button>
      </ion-item>
    
      <!--Invites-->
      <h4 v-if="groupInfo.open_invites.length">Uitnodigingen verzonden naar:</h4>
      <ion-list v-for="invite in groupInfo.open_invites" :key="invite.invitees.id">
        <ion-item>
          <ion-label color="dark">
            <p>{{invite.invitees.email}}</p>
          </ion-label>
        </ion-item>
      </ion-list>
    </div>

    <!--Stores-->
    <ion-button @click="flipStoreList">Winkel (soort)</ion-button>
    <div class="stores-section" v-if="showStores">
      <ion-list v-for="store in groupInfo.stores" :key="store.id">
        <ion-item>
          <ion-label color="dark">
            <h2>{{store.name}}</h2>
            <p>{{store.description}}</p>
          </ion-label>
        </ion-item>
      </ion-list>
      <p v-if="groupInfo.adminCheck.is_admin!=1">Groep admins en de oprichter kunnen winkels en winkeltypen toevoegen</p>
      <ion-button v-if="groupInfo.adminCheck.is_admin==1" class="ion-margin-top" @click="storeFormHidden = false">+</ion-button>
      <ion-item v-if="!storeFormHidden">
        <ion-label position="stacked">Winkelnaam</ion-label>
        <ion-input type="text" required="true" v-model="storeForm.name" placeholder="Voorbeeld: Buurtboer"></ion-input>
        <ion-label position="stacked">Omgeschrijving</ion-label>
        <ion-input type="text" required="true" v-model="storeForm.description" placeholder="Voorbeeld: supermarkt"></ion-input>
        <ion-button @click="addStore">Toevoegen</ion-button>
      </ion-item>
    </div>
  </div>
    <ion-button @click="goToGroupHistory">Aankoop historie</ion-button>
    <ion-button @click="closeModal">Sluit</ion-button>
    <ion-button color="danger">Group verlaten</ion-button>
</template>

<script>
import axios from 'axios'
import {
 IonButton, modalController, IonList, IonLabel, IonDatetime, IonItem, IonInput, IonCheckbox
} from "@ionic/vue";

import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'GroupDetailModal',
    components: {
    IonButton, IonList, IonLabel, IonDatetime, IonItem, IonInput, IonCheckbox
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
      showParticipants: false,
      showStores: false,
      groupInfo: {
        name:'',
        adminCheck:{
          is_admin:null
        },
        users:[],
        open_invites:[],
      },
      form: {
        invitee:"",
        groupId:this.groupId,
      },
      storeForm:{
        name:"",
        groupId:this.groupId,
        description:"",
      }
    }
  },
  setup() {
    const closeModal = () => {
    modalController.dismiss();
    }
    return { closeModal }
  },
  methods: {
    sendInvite(){
      axios.post('/inviteuser', this.form)
      .then(this.closeModal)
      .catch(error => {
        this.errorMessage = error.message;
        console.error('there was en error!', error)
      })
    },
    addStore(){
      axios.post('/addstore', this.storeForm)
      .then(this.closeModal)
      .catch(error=> {
        this.errorMessage = error.message;
        console.error('there was een error!', error)
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

ion-button{
  min-height: 36px;
}

#inviteForm{
  padding-top: 5px;
  padding-bottom: 5px;
  border: 1px solid black;
  border-radius: 10px;
}
</style>