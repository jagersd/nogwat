<template>
  <master-layout pageTitle="About page">
    <h1>This is an about page</h1>
    <p>Hi {{userInfo.name}}!</p>
  </master-layout>
</template>

<script>
import MasterLayout from '@/components/MasterLayout.vue'
import axios from 'axios'
export default {
  components: {MasterLayout},
  data: () => {
    return {
      userInfo: {
        name:null
      }
    }
  },
  beforeMount() {
    axios.get('/me')
    .then(response => (this.userInfo = response.data))
  },
}
</script>