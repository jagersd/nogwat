<template>
<div class="container">
  <h1>{{groupInfo.name}}</h1>
  <i>{{groupInfo.admin_instructions}}</i>
  <hr>
  <p>Groep aangemaakt op:
  <ion-datetime display-format="DD-MM-YYYY" :value="groupInfo.created_at"></ion-datetime>
  </p>
  
  <h4>Deelnemers</h4>
  <ion-list v-for="user in groupInfo.users" :key="user.id">
    <ion-item>
    <ion-label color="dark">
      <h2>{{user.name}}</h2>
      <p>{{user.email}}</p>
    </ion-label>

    </ion-item>
  </ion-list>
</div>
  <ion-button @click="closeModal">Sluit</ion-button>
  <ion-button color="danger">Group verlaten</ion-button>
</template>

<script>
import axios from 'axios'
import {
 IonButton, modalController, IonList, IonLabel, IonDatetime, IonItem
} from "@ionic/vue";

import { defineComponent } from 'vue'

export default defineComponent ({
  name: 'GroupDetailModal',
    components: {
    IonButton, IonList, IonLabel, IonDatetime, IonItem
  },
  props: ['groupId'],

  async created() {
    axios.get('/groupdetails/'+this.groupId)
    .then(response => (this.groupInfo = response.data))
    .catch(error => console.log(error))
  },
  
  data() {
    return {
      groupInfo: "",
    }
  },
  setup() {
  const closeModal = () => {
    modalController.dismiss();
  }
  return { closeModal }
  },
  methods: {

  }
});
</script>

<style scoped>
h1, h4, p, i{
  color: black;
}
</style>