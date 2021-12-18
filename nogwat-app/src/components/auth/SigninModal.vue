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
          <ion-label position="floating">Password</ion-label>
          <ion-input type="password" name="password" v-model="password"></ion-input>
        </ion-item>
        <ion-item>
        <ion-label position="floating">en / nl</ion-label>
          <ion-select ok-text="Ok" value="nl" v-model="userLocale">
            <ion-select-option value="nl">Nederlands</ion-select-option>
            <ion-select-option value="en">English</ion-select-option>
          </ion-select>
        </ion-item>
        <ion-button expand="full" @click="login()">Login</ion-button>
        <ion-button @click="closeModal">Sluit</ion-button>
      </ion-card-content>
    </ion-card>
</template>

<script>
import {
  IonCard,IonCardHeader,IonCardTitle,IonCardContent,IonItem,IonLabel,IonInput,IonButton,modalController,IonSelect, IonSelectOption
} from "@ionic/vue";
import { defineComponent } from 'vue';
import axios from 'axios'

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
          console.log(err)
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
  }
});
</script>