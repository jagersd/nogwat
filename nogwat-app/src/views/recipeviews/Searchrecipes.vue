<template>
  <master-layout pageTitle="Gevonden Recepten">
    <ion-list v-for="foundRecipe in foundRecipes" :key="foundRecipe.id">
      <ion-item @click="openDetailRecipeModal(foundRecipe)">
        <ion-label>{{ foundRecipe.name }}
          <p>{{ foundRecipe.description }}</p>
        </ion-label>
        <small id="favo-counter" slot="end" v-if="foundRecipe.favorited_count != 0"> <ion-icon :icon="star" color="primary"></ion-icon> ({{ foundRecipe.favorited_count }}) </small>
      </ion-item>
    </ion-list>
    <ion-item lines="none" id="expl">
      <small><i>Er worden maximaal 20 recepten geladen, specificeer zoekresultaten als je andere recepten wilt zien.</i></small>
    </ion-item>
  </master-layout>
</template>

<script>
import { IonList, IonItem, IonLabel, modalController, IonIcon } from "@ionic/vue";
import axios from "axios";
import detailRecipeModal from "@/components/recipes/detailRecipeModal.vue";
import {star} from "ionicons/icons"

export default {
  name: "Searchrecipes",
  components: { IonList, IonItem, IonLabel, IonIcon},
  setup(){
    return {star}
  },
  data() {
    return {
      foundRecipes: {},
      mealTypes: "",
      seachString: "",
    };
  },

  ionViewWillEnter() {
    this.initiateSearch()
  },
  methods: {
    async initiateSearch(){
      axios.get("/searchrecipes", {
        params: {
          mealTypes: JSON.parse(localStorage.getItem("searchParameters")).mealTypes,
          searchString: JSON.parse(localStorage.getItem("searchParameters")).searchString,
        },
      })
        .then((response) => (this.foundRecipes = response.data))
        .then(this.$store.commit('clearSeachParameters'))
        .catch((error) => console.log(error));
    },
    async openDetailRecipeModal(recipe) {
      const modal = await modalController.create({
        component: detailRecipeModal,
        componentProps: {
          recipeDetails: recipe,
        },
      });
      return modal.present();
    },
  },
};
</script>

<style scoped>
  #favo-counter{
    color: var(--ion-color-secondary);
    padding-left: 0;
    margin-left: 0;
  }
</style>
