<template>
  <master-layout pageTitle="Vaak op het lijstje">
    {{getData}}
  </master-layout>
</template>

<script>
import axios from 'axios'

export default {
  name: "StarItems",
  

  ionViewWillEnter(){
    this.initiateGetItems()
  },
  data(){
    return{
      getData: {}
    }
  },
  methods: {
    async initiateGetItems(){
      axios.get("/staritems")
      .then(response => (this.getData = response.data))
      .catch(error => console.log(error))
    }
  }
}
</script>