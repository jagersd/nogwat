<template>
  <master-layout pageTitle="Mijn Recepten">
    <ion-list v-for="myRecipe in myRecipes" :key="myRecipe.id">
      <ion-item @click="openDetailRecipeModal(myRecipe)">
        <ion-label
          >{{ myRecipe.name }}
          <p>{{ myRecipe.description }}</p>
        </ion-label>
      </ion-item>
    </ion-list>
  </master-layout>
</template>

<script>
import { IonList, IonItem, IonLabel, modalController } from "@ionic/vue";
import axios from "axios";
import detailRecipeModal from "@/components/recipes/detailRecipeModal.vue";

export default {
  name: "Myrecipes",
  components: { IonList, IonItem, IonLabel },
  data() {
    return {
      myRecipes: {},
    };
  },
  ionViewWillEnter() {
    axios
      .get("/myrecipes")
      .then((response) => (this.myRecipes = response.data))
      .catch((error) => console.log(error));
  },
  methods: {
    async openDetailRecipeModal(recipe) {
      const modal = await modalController.create({
        id: 'item-detail-modal',
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