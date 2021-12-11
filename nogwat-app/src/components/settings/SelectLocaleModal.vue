<template>
  <div class="container">
    <ion-item @click="setLocale('nl')" href="/">
      <ion-label>Nederlands</ion-label>
    </ion-item>
    <ion-item @click="setLocale('en')" href="/">
      <ion-label>English</ion-label>
    </ion-item>
  </div>
  <ion-button @click="closeModal">{{$t('misc.close')}}</ion-button>      
</template>

<script>
import {IonItem, IonLabel, IonButton, modalController} from "@ionic/vue"
import { defineComponent } from 'vue'

export default defineComponent({
  name:"SelectLocaleModal",
  components: {
    IonItem, IonLabel, IonButton,
  },
  setup() {
    const closeModal = () => {
      modalController.dismiss();
    };
    return { closeModal };
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