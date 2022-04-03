<template>
  <div class="container">
    <ion-button slot="end" @click="closeModal">{{$t('misc.close')}}</ion-button>
    <ion-list v-for="content in contents" :key="content.id" slot="content">
      <ion-item>
        <ion-text>
        <h5>{{content.title}}</h5>
        <div v-html="content.content"></div>
        <div v-html="content.content1"></div>
        <div v-html="content.content2"></div>
        </ion-text>
      </ion-item>
    </ion-list>
  </div>
</template>

<script>

import {
  IonItem,
  IonText,
  IonButton,
  IonList,
  modalController,
} from "@ionic/vue";
import { defineComponent } from "vue";

export default defineComponent({
  name: "DashboardModal",
  components: {
    IonItem,IonText, IonButton, IonList
  },
  props: ['content'],
  data(){
    return{
      contents: this.content
    }
  },
  methods:{
    closeModal(){
      modalController.dismiss()
    },
  }
})
</script>

<style scoped>
  .container{
    padding:1rem;
  }

  .container h5{
    text-align: center;
  }

</style>