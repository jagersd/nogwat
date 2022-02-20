<template>
  <div id="mainRecipeSection">
    <ion-item lines="none" color="primary">
      <ion-text>Deel je beste recepten met de rest van Nederland</ion-text>
    </ion-item>
    <ion-item>
      <ion-label position="floating">naam:</ion-label>
      <ion-input type="text" required="true" v-model="form.name"></ion-input>
    </ion-item>
    <ion-item>
      <ion-label position="floating">omschrijving:</ion-label>
      <ion-input
        type="text"
        required="true"
        v-model="form.description"
      ></ion-input>
    </ion-item>
    <ion-item>
      <ion-label position="floating">maaltijd soort:</ion-label>
      <ion-select
        aria-placeholder="diner"
        v-model="form.mealType"
        value="diner"
      >
        <ion-select-option value="ontbijt">ontbijt</ion-select-option>
        <ion-select-option value="ontbijt">Second Breakfast</ion-select-option>
        <ion-select-option value="lunch">lunch</ion-select-option>
        <ion-select-option value="snack">snack</ion-select-option>
        <ion-select-option value="diner">diner</ion-select-option>
      </ion-select>
    </ion-item>
    <ion-item>
      <ion-label position="floating">Aantal personen:</ion-label>
      <ion-select required="true" v-model="form.personAmount" value="1">
        <ion-select-option value="1">1</ion-select-option>
        <ion-select-option value="2">2</ion-select-option>
        <ion-select-option value="3">3</ion-select-option>
        <ion-select-option value="4">4</ion-select-option>
        <ion-select-option value="5">5</ion-select-option>
        <ion-select-option value="6">6</ion-select-option>
        <ion-select-option value="7">7</ion-select-option>
        <ion-select-option value="8">8</ion-select-option>
        <ion-select-option value="9">9</ion-select-option>
        <ion-select-option value="10">10</ion-select-option>
      </ion-select>
    </ion-item>
    <ion-item lines="none">
      <ion-label position="floating">Instructies: <small><i>{{charactersRemaining}}</i></small></ion-label>
      <ion-textarea
        type="text"
        required="true"
        autoGrow="true"
        inputmode="text"
        placeholder="Stap 1:...."
        maxlength="500"
        v-model="form.instructions"
      ></ion-textarea>
    </ion-item>
  </div>
  <div id="ingredienListSection">
    <div
      class="addIngredient"
      v-for="(ingredient, k) in form.ingredients"
      :key="k"
    >
      <ion-text color="primary">Ingrediënten</ion-text>
      <ion-item>
        <ion-label position="floating">Product</ion-label>
        <ion-input
          type="text"
          required="true"
          v-model="ingredient.name"
        ></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">Hoeveelheid</ion-label>
        <ion-input type="number" v-model="ingredient.amount"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">Eenheid</ion-label>
        <ion-select v-model="ingredient.amountType" interface="popover">
          <ion-select-option value="st">stuks</ion-select-option>
          <ion-select-option value="el">el (eetlepel)</ion-select-option>
          <ion-select-option value="tl">tl (theelepel)</ion-select-option>
          <ion-select-option value="gr">gr (gram)</ion-select-option>
          <ion-select-option value="kg">kg (kilo)</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-button @click="addIngredient()">+</ion-button>
    </div>
    <ion-item>
      <small>Alleen door jou te vinden?</small>
      <ion-checkbox v-model="form.private" slot="end"></ion-checkbox>
    </ion-item>
    <ion-button @click="saveRecipe">Opslaan</ion-button>
    <ion-button @click="closeModal">Sluit</ion-button>
  </div>
</template>

<script>
import axios from "axios";
import {
  IonText,
  modalController,
  IonButton,
  IonLabel,
  IonInput,
  IonItem,
  IonSelect,
  IonSelectOption,
  IonTextarea,
  IonCheckbox,
  toastController
} from "@ionic/vue";
import { defineComponent } from "vue";

export default defineComponent({
  name: "AddRecipeModal",
  components: {
    IonText,
    IonButton,
    IonLabel,
    IonInput,
    IonItem,
    IonSelect,
    IonSelectOption,
    IonTextarea,
    IonCheckbox,
  },
  data() {
    return {
      maxCharacters: 500,
      form: {
        name: "",
        description: "",
        mealType: "diner",
        instructions: "",
        personAmount: "1",
        private: false,
        ingredients: [
          {
            name: "",
            amountType: "gr",
            amount: null,
          },
        ],
      },
    };
  },
  computed:{
    charactersRemaining: function() {
      return this.maxCharacters - this.form.instructions.length
    }
  },
  methods: {
    addIngredient() {
      this.form.ingredients.push({
        name: "",
        amountType: "",
        amount: null,
      });
    },
    saveRecipe() {
      let checkerArray = []
      this.form.ingredients.forEach(ingredient => checkerArray.push(Object.values(ingredient).includes('')))
      if(checkerArray.includes(true) || this.form.name == "" || this.form.description == "" || this.form.instructions == ""){
        this.toastResponse()
      }else{
        axios.post("/createrecipe", this.form)
        .then(this.closeModal)
        .catch((error) => {
          this.errorMessage = error.message;
          console.error("This was an error!", error);
        });
      }
    },
    async toastResponse(){
      const toast = await toastController
      .create({
        message:'Niet alle velden zijn gevuld',
        color:'dark',
        position:'middle',
        duration: 2000
      })
      return toast.present()
    }
  },

  setup() {
    const closeModal = () => {
      modalController.dismiss();
    };
    return { closeModal };
  },
});
</script>

<style scoped>
#mainRecipeSection {
  min-height: 50vh;
  max-height: 60vh;
  overflow-x: scroll;
}

#ingredienListSection {
  border-top: 2px solid var(--ion-color-primary);
  min-height: 40vh;
  overflow-x: scroll;
  padding-left: 1rem;
  padding-top: 1rem;
}

#addRecipeModal {
  min-height: 100%;
}
</style>