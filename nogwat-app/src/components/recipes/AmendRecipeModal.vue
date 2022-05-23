<template>
  <ion-item lines="none" color="primary">
    <ion-text class="ion-text-center"><p>{{$t('recipes.amend.slogan')}}</p></ion-text>
  </ion-item>
  <div class="container">
  <swiper @swiper="onSwiper" :space-between="50">
    <swiper-slide>
      <ion-item lines="none" class="ion-text-end">
        <p class="slide-header">{{$t('recipes.add.ingredients')}}</p>
        <ion-icon :icon="chevronForward" color="primary"></ion-icon>
      </ion-item>
      <ion-item>
        <ion-label position="floating">{{$t('recipes.add.name')}}:</ion-label>
        <ion-input type="text" required="true" v-model="form.name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">{{$t('misc.description')}}:</ion-label>
        <ion-input
          type="text"
          required="true"
          v-model="form.description"
        ></ion-input>
      </ion-item>
      <ion-item>
        <ion-label position="floating">{{$t('recipes.add.type')}}:</ion-label>
        <ion-select
          aria-placeholder="diner"
          v-model="form.mealType"
          value="diner"
          interface="popover"
        >
          <ion-select-option value="ontbijt">{{$t('recipes.categories.breakfast')}}</ion-select-option>
          <ion-select-option value="ontbijt">Second Breakfast</ion-select-option>
          <ion-select-option value="lunch">{{$t('recipes.categories.lunch')}}</ion-select-option>
          <ion-select-option value="snack">{{$t('recipes.categories.snack')}}</ion-select-option>
          <ion-select-option value="diner">{{$t('recipes.categories.dinner')}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item>
        <ion-label position="floating">{{$t('recipes.add.pamount')}}:</ion-label>
        <ion-select required="true" v-model="form.personAmount" interface="popover">
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
        <ion-label position="floating">{{$t('recipes.add.instructions')}}: <small><i>{{charactersRemaining}}</i></small></ion-label>
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
    </swiper-slide>
    <swiper-slide>
      <ion-item lines="none" class="ion-text-end">
        <ion-icon :icon="chevronBack" color="primary"></ion-icon>
        <p class="slide-header">{{$t('misc.description')}}</p>
      </ion-item>
      <ion-button v-if="!ingredientsLoaded" @click="parseAmendableRecipe()" size="small">{{$t('recipes.amend.useIngredients')}}</ion-button>
      <div
        class="addIngredient"
        v-for="(ingredient, k) in form.ingredients"
        :key="k"
      >
        <ion-button @click="addIngredient()">+</ion-button>
        <ion-button v-if="ingredientLength > 1" @click="removeIngredient(k)" color="tertiary">-</ion-button>
        <ion-item>
          <ion-label position="floating">{{$t('recipes.add.product')}}</ion-label>
          <ion-input
            type="text"
            required="true"
            v-model="ingredient.name"
          ></ion-input>
        </ion-item>
        <ion-item>
          <ion-label position="floating">{{$t('recipes.add.amount')}}</ion-label>
          <ion-input type="number" v-model="ingredient.amount"></ion-input>
        </ion-item>
        <ion-item>
          <ion-label position="floating">{{$t('recipes.add.unit')}}</ion-label>
          <ion-select v-model="ingredient.amountType" interface="popover">
            <ion-select-option value="st">{{$t('misc.measurements.pc')}}</ion-select-option>
            <ion-select-option value="el">{{$t('misc.measurements.el')}}</ion-select-option>
            <ion-select-option value="tl">{{$t('misc.measurements.tl')}}</ion-select-option>
            <ion-select-option value="gr">{{$t('misc.measurements.gr')}}</ion-select-option>
            <ion-select-option value="kg">{{$t('misc.measurements.kg')}}</ion-select-option>
            <ion-select-option value="ml">{{$t('misc.measurements.ml')}}</ion-select-option>
            <ion-select-option value="dl">{{$t('misc.measurements.dl')}}</ion-select-option>
            <ion-select-option value="l">{{$t('misc.measurements.l')}}</ion-select-option>
          </ion-select>
        </ion-item>
      </div>
    </swiper-slide>
  </swiper>
  </div>
  <div class="bottom-menu">
  <ion-item>
    <small>{{$t('recipes.private')}}</small>
    <ion-checkbox v-model="form.private" slot="end"></ion-checkbox>
  </ion-item>
  <ion-button @click="saveRecipe">{{$t('misc.save')}}</ion-button>
  <ion-button @click="closeModal">{{$t('misc.close')}}</ion-button>
  </div>
</template>

<script>
import axios from "axios";
import { chevronBack, chevronForward } from "ionicons/icons";
import { Swiper, SwiperSlide} from 'swiper/vue';
import '@ionic/vue/css/ionic-swiper.css';
import 'swiper/swiper.min.css';

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
  toastController,
  IonIcon,
} from "@ionic/vue";
import { defineComponent } from "vue";


export default defineComponent({
  name: "AmendRecipeModal",
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
    IonIcon,
    Swiper,
    SwiperSlide,
  },
  props: {
		recipeToAmend: {
      type: Object,
      required: true
    },
	},
  setup() {
    const closeModal = () => {
      modalController.dismiss();
    };
    const onSwiper = (swiper) => {
      setTimeout(function () {
        swiper.update();
      }, 500);
    };

    return { closeModal , onSwiper, chevronBack, chevronForward};
  },
  data() {
    return {
      ingredientsLoaded: false,
      maxCharacters: 500,
      form: {
        recipeId: this.recipeToAmend.id,
        mode: "amend",
        name: this.recipeToAmend.name,
        description: this.recipeToAmend.description,
        mealType: this.recipeToAmend.meal_type,
        instructions: this.recipeToAmend.instructions,
        personAmount: parseInt(this.recipeToAmend.person_amount),
        lang: localStorage.getItem('locale'),
        private: this.recipeToAmend.private,
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
    },
    ingredientLength: function(){
      return this.form.ingredients.length
    },
  },
  methods: {
    parseAmendableRecipe(){
      if(this.form.ingredients[0].name==""){
        this.form.ingredients.shift()
      }
      this.recipeToAmend.recipe_items.forEach(element => {
        this.form.ingredients.push({
          name: element.item_name,
          amountType: element.measurement.abbreviation,
          amount: element.measurement_amount
        })
      })
      this.ingredientsLoaded = true
    },
    addIngredient() {
      this.form.ingredients.unshift({
        name: "",
        amountType: "",
        amount: null,
      })
    },
    removeIngredient(key){
      this.form.ingredients.splice(key, 1)
    },
    saveRecipe() {
      let checkerArray = []
      this.form.ingredients.forEach(ingredient => checkerArray.push(Object.values(ingredient).includes('')))
      if(checkerArray.includes(true) || this.form.name == "" || this.form.description == "" || this.form.instructions == ""){
        this.toastResponse()
      }else{
        axios.post("/amendrecipe", this.form)
        .then(modalController.dismiss())
        .then(modalController.dismiss({message: 'I am closing a modal'}, 'confirm', 'recipe-detail-modal'))
        .then(this.$router.push({name:'recipesmenu'}))
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

  
});
</script>

<style scoped>

ion-textarea{
  min-height: 20vh;
}

.bottom-menu{
  min-height: 15vh;
}

.bottom-menu ion-button{
  margin-left: 20px;
}

.swiper-slide{
  max-width: 100vw;
  margin-top: 20px;
  height: 80vh;
  border: 5px solid var(--ion-color-primary);
  border-radius: 10px;
  padding: 10px;
  overflow-x: scroll;
}

.slide-header{
  font-size: large;
  font-weight: bold;
  color:var(--ion-color-primary)
}

</style>