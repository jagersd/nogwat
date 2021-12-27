<template>
    <ion-card>
      <ion-card-header>
        <ion-card-title>Inloggen</ion-card-title>
      </ion-card-header>
      <ion-card-content>
        <ion-item>
          <ion-label position="floating">Email address</ion-label>
          <ion-input type="email" name="email" v-model="email"></ion-input>
        </ion-item>
        <ion-item>
          <ion-label position="floating">Wachtwoord</ion-label>
          <ion-input type="password" name="password" v-model="password"></ion-input>
        </ion-item>
        <ion-item>
        <ion-label position="floating">en / nl</ion-label>
          <ion-select ok-text="Ok" value="nl" v-model="userLocale">
            <ion-select-option value="nl">Nederlands</ion-select-option>
            <ion-select-option value="en">English</ion-select-option>
          </ion-select>
        </ion-item>
        <ion-item lines="none" color="danger" id="error-message" v-if="incorrectCreds">
          <p>Gebruikersnaam / wachtwoord combinatie incorrect.</p>
        </ion-item>
        <ion-button expand="full" @click="login()">Login</ion-button>
        <ion-button expand="full" @click="closeModal">Sluit</ion-button>
        <ion-button expand="full" @click="forgotPassword" color="secondary" slot="end">Wachtwoord vergeten</ion-button>
      </ion-card-content>
    </ion-card>
</template>

<script>
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController,IonSelect, IonSelectOption
} from "@ionic/vue";
import { defineComponent } from 'vue'
import axios from 'axios'
import ResetPasswordModal from './ResetPasswordModal.vue'

export default defineComponent({
  name: 'SigninModal',
  components: {
    IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,IonSelect, IonSelectOption
  },
  data () {
    return {
      email: '',
      password: '',
      userLocale: 'nl',
      groupData:{
        groups:[
          {
            id: null,
            name: '',
          }
        ]
      },
      incorrectCreds: false,
    }
  },
  setup() {
    const closeModal = () => {
      modalController.dismiss();
    }
    return { closeModal }
  },
  methods: {
    login () {
      this.$store
        .dispatch('login', {
          email: this.email,
          password: this.password,
          userLocale: this.userLocale
        })
        .then(this.$store.commit('setUserLocale', this.userLocale))
        .then(this.closeModal)
        .then(() => {
          if(!JSON.parse(localStorage.getItem("groups"))){
            this.checkForGroups()
          } else {
            this.$router.push({ name: 'lists' })
          }
        })
        .catch(err => {
          err.response.status == 404 ? this.incorrectCreds = true : this.incorrectCreds = false
        })
    },
    checkForGroups(){
      axios.get('/mygroups')
      .then(response => (this.groupData = response.data))
      .then(() => {
        if(this.groupData.groups.length){
        this.$store.commit('setGroupData', {
        groupId: this.groupData.groups[0].id, 
        groupName:this.groupData.groups[0].name}
        )}
      })
      .catch(error => console.log(error))
    },
    async forgotPassword(){
      const modal = await modalController.create({
        component: ResetPasswordModal
      })
      return modal.present()
    }
  }
});
</script>

<style scoped>
  #error-message{
    margin-top: 1rem;
    border-radius: 10px;
  }
</style>