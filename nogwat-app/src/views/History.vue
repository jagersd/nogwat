<template>
  <master-layout :pageTitle="$t('groups.history.titel')">
    <ion-item>
      <ion-label>
        <p>{{$t('groups.history.headers')}}</p>
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
          <h5 v-else>
            {{ listItem.item_name}} |
            {{ listItem.added_user.name }} |
            {{ listItem.purchased_user.name }} 
          </h5>
          <div class="details" v-if="showDetails == listItem.id">
            <ion-label>
              <p>
              {{$t('groups.history.added')}} 
              {{formatDate(listItem.created_at)}}
              </p>
            </ion-label>
            <ion-label>
              <p>
              {{$t('groups.history.bought')}} 
              {{formatDate(listItem.date_purchased)}}
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
} from "@ionic/vue";
import moment from 'moment'

export default {
  components: { IonList, IonLabel, IonItem},
  ionViewWillEnter() {
    this.initiateList()
  },
  data(){
    return {
      listInfo:{
      },
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
    formatDate(value){
      return moment(value).format("D MMM YYYY")
    }
  },
};
</script>