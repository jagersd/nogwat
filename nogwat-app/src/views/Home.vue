<template>    
  <master-layout pageTitle="Home">
    <div id="container">
      <ion-button expand="block" @click="toLogin()">SignIn</ion-button>
    </div>
  </master-layout>
</template>

<script>
import { IonButton } from '@ionic/vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Home',
  components: {
    IonButton
  },

  methods: {
    toLogin () {
      this.$router.push({ path: '/login'})
    }
  }

});
</script>

<style scoped>
#container {
  text-align: center;
  
  position: absolute;
  left: 0;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

#container strong {
  font-size: 20px;
  line-height: 26px;
}

#container p {
  font-size: 16px;
  line-height: 22px;
  
  color: #8c8c8c;
  
  margin: 0;
}

#container a {
  text-decoration: none;
}
</style>