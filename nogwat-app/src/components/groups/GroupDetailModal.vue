<template>
<!--GENERAL-->
  <div class="container">
    <h1>{{groupInfo.name}}</h1>
    <i>{{groupInfo.admin_instructions}}</i>
    
    <hr>
    <p>Groep aangemaakt op:
    <ion-datetime display-format="DD-MM-YYYY" :value="groupInfo.created_at"></ion-datetime>
    </p>
<!--ParticipantList-->    
    <h4>Deelnemers</h4>
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
<!--Uitnodigingen-->
    <h4 v-if="groupInfo.open_invites.length">Uitnodigingen verzonden naar:</h4>
    <ion-list v-for="invite in groupInfo.open_invites" :key="invite.invitees.id">
      <ion-item>
        <ion-label color="dark">
          <p>{{invite.invitees.email}}</p>
        </ion-label>
      </ion-item>
    </ion-list>
  </div>
  <item-item>
    <ion-checkbox slot=start @click="setDefaultGroup"></ion-checkbox>
    <ion-label color="secondary">Instellen standaard groep</ion-label>
  </item-item>
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
      formHidden: true,
      groupInfo: {
        adminCheck:{
          is_admin:null
        },
        users:[],
        open_invites:[],
      },
      form: {
        invitee:"",
        groupId:this.groupId,
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
    setDefaultGroup(){
      this.$store.commit('setDefaultGroup', this.groupId)
    }
  }
});
</script>

<style scoped>
h1, h4, p, i{
  color: black;
}

#inviteForm{
  padding-top: 5px;
  padding-bottom: 5px;
  border: 1px solid black;
  border-radius: 10px;
}
</style>