<template>
  <master-layout pageTitle="Gevonden Recepten">
    <ion-list v-for="foundRecipe in foundRecipes" :key="foundRecipe.id">
      <ion-item @click="openDetailRecipeModal(foundRecipe)">
        <ion-label>{{ foundRecipe.name }}
          <p>{{ foundRecipe.description }}</p>
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
  name: "Searchrecipes",
  components: { IonList, IonItem, IonLabel },
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
			console.log(this.mealTypes)
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