<template>
  <master-layout pageTitle="Historie">
    <ion-item>
      <ion-label>
        <p>Lijst item | gekocht door | Toegevoegd door</p>
      </ion-label> 
    </ion-item>
    <ion-list v-for="listItem in listInfo" :key="listItem.id">
      <ion-item>
        <ion-label @click="showDetails = listItem.id">
          <h5 v-if="listItem.measurement_amount">
            {{ listItem.measurement_amount }}
            {{ listItem.measurement.abbreviation }}
            {{ listItem.item_name}} |
            {{ listItem.added_user.name }} |
            {{ listItem.purchased_user.name }} 
          </h5>
          <div class="details" v-if="showDetails == listItem.id">
            <ion-label>
              <p>
              Toegevoegd op: 
              <ion-datetime readonly="true" display-format="DD-MM-YYYY" :value="listItem.created_at"></ion-datetime>
              </p>
            </ion-label>
            <ion-label>
              <p>
              Aangekocht op: 
              <ion-datetime readonly="true" display-format="DD-MM-YYYY" :value="listItem.date_purchased"></ion-datetime>
              </p>
            </ion-label>
          </div>
        </ion-label>
      </ion-item>
  </ion-list>
  </master-layout>
</template>

<script>
import axios from "axios";
import {
  IonList,
  IonLabel,
  IonItem,
  IonDatetime,
} from "@ionic/vue";

export default {
  components: { IonList, IonLabel, IonItem, IonDatetime},
  ionViewWillEnter() {
    this.initiateList()
  },
  data(){
    return {
      listInfo:{},
      showDetails: null
    }
  },

  methods: {
    async initiateList() {
      axios.get("/gethistory", {
        params: {
          groupId: this.$route.params.groupId,
        },
      })
      .then((response) => (this.listInfo = response.data))
      .catch((error) => console.log(error))
    },
  },
};
</script>