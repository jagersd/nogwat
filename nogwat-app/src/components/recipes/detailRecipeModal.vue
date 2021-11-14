<template>
  <ion-card color="primary">
    <ion-card-header>
      <ion-card-title>
        {{ recipeDetails.name }}
      </ion-card-title>
      <ion-card-subtitle>
        Gedeeld door: {{ recipeDetails.user.name }}
      </ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
      <i>{{ recipeDetails.description }}</i>
      <ion-icon v-if="recipeFavorited == 'true'" id="favo-icon" slot="end" :icon="star" color="warning" @click="removeFromFavorites()"></ion-icon>
      <ion-icon v-if="recipeFavorited == 'false'" id="favo-icon" slot="end" :icon="starOutline" color="warning" @click="addToFavorites()"></ion-icon>
      <h2 id="ingredient-header">
        Ingredienten voor {{ personAmount }} personen
      </h2>
      <ion-range
				v-model="personAmount"
        min="1"
        max="10"
        step="1"
        :value="personAmount"
        snaps
        color="dark">
        <ion-icon slot="start" size="small" :icon="person"></ion-icon>
        <ion-icon slot="end" size="small" :icon="people"></ion-icon>
      </ion-range>

      <ion-list
        class="ingredient-list"
        inset="true"
        v-for="ingredient in recipeDetails.recipe_items"
        :key="ingredient.id">
        <ion-item lines="none" id="list-item">
          {{ (ingredient.measurement_amount / originalPersonAmount) * personAmount  }} 
          {{ ingredient.measurement.full_name }} 
          {{ ingredient.item_name }}
          <ion-checkbox checked v-model="ingredient.checkedForList" color="primary" slot="end" :value="ingredient.id"></ion-checkbox>
        </ion-item>
      </ion-list>
    </ion-card-content>
  </ion-card>
  <ion-text color="secondary">
    <p>{{ recipeDetails.instructions }}</p>
  </ion-text>

  <ion-button expand="block" @click="addToList">Voeg toe aan lijst</ion-button>
  <ion-button expand="block" @click="closeModal">Sluit</ion-button>
</template>

<script>
import axios from 'axios'
import { defineComponent } from "vue";
import {
  modalController,
  IonButton,
  IonCard,
  IonCardHeader,
  IonCardSubtitle,
  IonCardTitle,
  IonCardContent,
  IonList,
  IonRange,
  IonText,
  IonIcon,
  IonCheckbox,
  IonItem,
} from "@ionic/vue";
import { person, people, star, starOutline } from "ionicons/icons";

export default defineComponent({
  name: "detailRecipeModal",
  components: {
    IonButton,
    IonCard,
    IonCardHeader,
    IonCardSubtitle,
    IonCardTitle,
    IonCardContent,
    IonList,
    IonRange,
    IonText,
    IonIcon,
    IonCheckbox,
    IonItem
  },
  props: {
		recipeDetails: Object
	},
  setup() {
    const closeModal = () => {
      modalController.dismiss()
    }
    return { closeModal, person, people, star, starOutline }
  },
  ionViewDidEnter(){
    this.recipeDetails.recipe_items.forEach((item) =>{
      item.checkedForList = true;
    })
  },
  data() {
    return {
			originalPersonAmount: this.recipeDetails.person_amount,
			personAmount: this.recipeDetails.person_amount,
      recipeFavorited: this.recipeDetails.favorited,
			form: {
				listItems:[]
			},
      favoForm: {
        recipeId: this.recipeDetails.id
      },
      
    }
  },
	methods:{
		addToList(){
			this.form.listItems = [];
			this.recipeDetails.recipe_items.forEach((value) => {
        if (value.checkedForList !== false){
        this.form.listItems.push({
          groupId: JSON.parse(localStorage.getItem('group')).groupId,
          itemName: value.item_name,
          measurementType: value.measurement.abbreviation,
          amount: (value.measurement_amount/this.originalPersonAmount) * this.personAmount,
          recipeId: this.recipeDetails.id,
        })
      }
		})
			axios.post('/additem', this.form)
        .then(this.closeModal)

        .catch(error => {
        this.errorMessage = error.message;
        console.error("There was an error!", error);
      })
		},
    testMethod(){
      this.recipeDetails.recipe_items.forEach((item) =>{
          console.log(item.checkedForList)

      })
    },
    addToFavorites(){
      axios.post('/addfavorite',this.favoForm)
      .then(this.recipeFavorited = 'true')
      .catch(error => {
        this.errorMessage = error.message;
        console.error("There was an error!", error);
      })
    },
    removeFromFavorites(){
      axios.post('/removefavorite',this.favoForm)
      .then(this.recipeFavorited = 'false')
      .catch(error => {
        this.errorMessage = error.message;
        console.error("There was an error!", error);
      })
    }
	}
});
</script>

<style scoped>
#favo-icon{
  font-size: large;
  position: absolute;
  right: 2vw;
}
.ingredient-list {
  color: black;
  padding: 0;
}

#ingredient-header {
  margin-top: 10px;
}

ion-checkbox{
  --border-radius: 50px;
}

ion-text {
  padding: 2rem;
}
</style>