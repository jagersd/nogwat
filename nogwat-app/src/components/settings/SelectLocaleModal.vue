<template>
  <div class="container">
    <ion-item @click="setLocale('nl')">
      <ion-label>Nederlands</ion-label>
    </ion-item>
    <ion-item @click="setLocale('en')">
      <ion-label>English</ion-label>
    </ion-item>
  </div>      
</template>

<script>
import {IonItem, IonLabel, modalController} from "@ionic/vue"
import { defineComponent } from 'vue'

export default defineComponent({
  name:"SelectLocaleModal",
  components: {
    IonItem, IonLabel,
  },
  data(){
    return{
      userLocale: localStorage.getItem("locale")
    }
  },
  methods: {
    setLocale(shortCode){
      this.$store.commit('setUserLocale',shortCode)
      modalController.dismiss()
    }
  }

})
</script>

<style scoped>
ion-item{
  margin-top: 5vh;
  text-align: center;
}
</style>