<template>
  <master-layout pageTitle="Recepten">
	<div class="container">
    <ion-text>Wat eten we vanavond?</ion-text>
		<ion-list>
      <ion-button expand="block" router-link="/myrecipes">Mijn Recepten</ion-button>
      <ion-button expand="block" router-link="/myfavorites">Mijn Favoriete Recepten</ion-button>
      <ion-button expand="block" @click="flipSearchbox()">Zoeken</ion-button>
      <ion-card color="tertiary" v-if="showSearchbox == true">
        <ion-card-content>
          <ul>
            <li>
              <ion-checkbox @click="setMealTypesArray('ontbijt')"></ion-checkbox>
              <ion-label>Ontbijt</ion-label>
            </li>
            <li>
              <ion-checkbox @click="setMealTypesArray('snack')"></ion-checkbox>
              <ion-label>Snack</ion-label>
            </li>
            <li>
              <ion-checkbox @click="setMealTypesArray('lunch')"></ion-checkbox>
              <ion-label>Lunch</ion-label>
            </li>
            <li>
              <ion-checkbox @click="setMealTypesArray('diner')" checked></ion-checkbox>
              <ion-label>Diner</ion-label>
            </li>
          </ul>
          <ion-item>
            <ion-label position="floating" color="secondary">Zoekwoord (optioneel)</ion-label>
            <ion-input v-model="searchString" color="secondary" type="text"></ion-input>
          </ion-item>
          <ion-button @click="toSearchResults()">OK</ion-button>
        </ion-card-content>
      </ion-card>
      <ion-button color="tertiary" class="ion-margin-top" @click="openAddRecipeModal()">+ voeg recept toe</ion-button>
		</ion-list>
	</div>
  </master-layout>
</template>

<script>
import {
  IonButton,
  IonText,
  IonList,
  modalController,
  IonCheckbox,
  IonInput,
  IonLabel,
	IonCard,
	IonCardContent,
	IonItem,
} from "@ionic/vue";
import AddRecipeModal from "../components/recipes/AddRecipeModal.vue";

export default {
  name: "Recipes",
  components: {
    IonButton,
    IonText,
    IonList,
    IonCheckbox,
    IonInput,
    IonLabel,
	IonCard,
	IonCardContent,
	IonItem,
  },
	async created() {
		this.$store.commit('clearSeachParameters')
	},
	data() {
		return{
			showSearchbox: false,
			mealTypes:['diner'],
			searchString:"",
		}
	},
  methods: {
    async openAddRecipeModal() {
      const modal = await modalController.create({
        component: AddRecipeModal,
        cssClass: "addRecipeModal",
      });
      return modal.present();
    },
		flipSearchbox(){
			this.showSearchbox == false ? this.showSearchbox = true : this.showSearchbox =false
		},
		setMealTypesArray(value){
			if (this.mealTypes.includes(value)){
				for(let i = 0; i < this.mealTypes.length; i++){ 
					if (this.mealTypes[i] === value) { 
						this.mealTypes.splice(i, 1); 
					}
				}
			} else {
				this.mealTypes.push(value)
			}
		},
		toSearchResults(){
			this.$store.commit('setSearchParameters', {
				mealTypes: this.mealTypes.toString(),
				searchString: this.searchString
			})
			this.$router.push({ path: '/searchrecipes' })
		}
  },
};
</script>

<style scoped>

  .container{
    padding: 1rem;
  }

	ion-checkbox{
		margin-right: 5px;
	}

	ul{
		list-style: none;
	}

	li{
		padding: 5px;
	}
	
	ion-item{
		border-radius: 10px;
	}
</style>