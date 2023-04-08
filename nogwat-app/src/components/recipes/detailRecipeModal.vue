<template>
  <div class="container">
  <ion-card color="primary">
    <ion-card-header>
      <ion-card-title>
        {{ recipeDetails.name }}
        <ion-button v-if="(recipeDetails.user_id_created == $store.state.user.user.id)" color="danger" size="small" id="recipe-remove-btn" @click="removeRecipeActionSheet">X</ion-button>
        <ion-icon v-if="(recipeDetails.user_id_created == $store.state.user.user.id)" :icon="pencil" color="tertiary" @click="amendRecipe()"></ion-icon>
      </ion-card-title>
      <ion-card-subtitle>
        <p>Gedeeld door: {{ recipeDetails.user_id_created == $store.state.user.user.id ?  'jou' : recipeDetails.user.name}}</p>
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
        <ion-item lines="none">
          <p id="list-item">
          {{ (ingredient.measurement_amount / originalPersonAmount) * personAmount  }} 
          {{ ingredient.measurement.full_name }} 
          {{ ingredient.item_name }}
          </p>
          <ion-checkbox checked v-model="ingredient.checkedForList" color="primary" slot="end" :value="ingredient.id"></ion-checkbox>
        </ion-item>
      </ion-list>
    </ion-card-content>
  </ion-card>
  <ion-text color="secondary">
    <div id="instructions-section" v-for="i in this.recipeDetails.instructions.split('. ')">
    {{i}}.<br>
    </div>
  </ion-text>
  </div>
  <ion-text color="danger" v-if="!groupChecker">Stel een standaard groep in om de ingredienten toe te voegen aan je boodschappenlijst.</ion-text>
  <ion-button class="menu-button" v-if="groupChecker" expand="block" @click="addToList">Voeg toe aan lijst</ion-button>
  <ion-button class="menu-button" color="tertiary" expand="block" @click="closeModal">Sluit</ion-button>
</template>

<script>
import axios from 'axios'
import { defineComponent } from "vue"

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
  actionSheetController,
} from "@ionic/vue";

import AmendRecipeModal from "../recipes/AmendRecipeModal.vue";
import { person, people, star, starOutline, removeCircle, pencil } from "ionicons/icons";

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
    modalController.dismiss();
    }
    return { closeModal, person, people, star, starOutline, removeCircle, pencil }
  },
  ionViewDidEnter(){
    this.recipeDetails.recipe_items.forEach((item) =>{
      item.checkedForList = true;
    })
  },
  data() {
    return {
      groupChecker: JSON.parse(localStorage.getItem('group')),
      originalPersonAmount: this.recipeDetails.person_amount,
      personAmount: this.recipeDetails.person_amount,
      recipeFavorited: this.recipeDetails.favorited,
      form: {
        listItems:[]
      },
      favoForm: {
        recipeId: this.recipeDetails.id
      },
      parsedInstructions: ""
    }
  },
  methods:{
    splitInstructions(instructionString){
      return instructionString.split(". ")
    },
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
    async amendRecipe(){
      const modal = await modalController.create({
        id:'amend-item-modal',
        component: AmendRecipeModal,
        cssClass: "amendRecipeModal",
        componentProps: {
          recipeToAmend: this.recipeDetails,
        },
      });
      return modal.present();
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
    },
    async removeRecipeActionSheet(){
      const removeRecipeAction = await actionSheetController
      .create({
        header:'Weet je zeker dat je dit recept wilt verwijderen?',
        buttons:[
          {
            text: 'Ja',
            handler:()=>{
              axios.post('/deleterecipe',{recipeId:this.recipeDetails.id})
              .then(this.closeModal)
              .then(this.$router.push({name: 'recipesmenu'}))
              .catch(error=>{
                this.errorMessage = error.message
                console.error(error)
              })
            }
          },
          {
            text: 'Nee',
            role: 'cancel'
          }
        ]
      })
      await removeRecipeAction.present()
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
#recipe-remove-btn{
  position: absolute;
  right: 2vw;
}
.ingredient-list {
  color: black;
  padding: 0;
  font-size: 10px;
}

.container{
  overflow-x: scroll;
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

.menu-button{
  min-height: 35px;
}

#instructions-section{
  margin-left: 15px;
  margin-right: 15px;
  padding: 0;
}
</style>
