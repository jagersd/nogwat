<template>
  <master-layout pageTitle="Vaak op het lijstje">
    <div class="container">
      <!--Group Loop-->
      <ion-list v-for="group in starData" :key="group.group_id">
        <ion-label color="primary">
          {{group.name}}
        </ion-label>
        <!--Item Loop-->
        <ion-list v-for="item in group.items" :key="item.item_name">
          <ion-item>
            {{item.item_name}} ( {{item.name_counter}}x)
          </ion-item>

        </ion-list>
      </ion-list>
    </div>
  </master-layout>
</template>

<script>
import axios from 'axios'
import { IonList, IonItem, IonLabel} from '@ionic/vue'

export default {
  name: "StarItems",
  components: {IonList, IonItem, IonLabel},

  ionViewWillEnter(){
    this.initiateGetItems()
  },
  data(){
    return{
      starData: {}
    }
  },
  methods: {
    async initiateGetItems(){
      axios.get("/staritems")
      .then(response => (this.starData = response.data))
      .catch(error => console.log(error))
    },
  }
}
</script>

<style scoped>
.container{
  padding-left: 10px;
}

</style>