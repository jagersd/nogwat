<template>
  <master-layout pageTitle="AddRecipe">
  <div class="container">
  <swiper @swiper="onSwiper" :space-between="50">
    <swiper-slide>
      <ion-item lines="none"> 
        <IonText slot="start" id="header-text" color="primary"><b>{{$t('recipes.add.new')}} (1/3)</b></IonText>
        <ion-icon slot="end" :icon="chevronForward" color="primary" size="large"></ion-icon>
      </ion-item>
      <ion-item>
        <ion-input :label="$t('recipes.add.name')" label-placement="floating" type="text" required="true" v-model="form.name"></ion-input>
      </ion-item>
      <ion-item>
        <ion-input
          :label="$t('misc.description')"
          label-placement="floating"
          type="text"
          required="true"
          v-model="form.description"
        ></ion-input>
      </ion-item>
      <ion-item>
        <ion-select
          :label="$t('recipes.add.type')"
          label-placement="floating"
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
          <ion-select-option value="dessert">{{$t('recipes.categories.dessert')}}</ion-select-option>
        </ion-select>
      </ion-item>
      <ion-item>
        <ion-select :label="$t('recipes.add.pamount')" label-placement="floating" required="true" v-model="form.personAmount" interface="popover">
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
        <small>{{$t('recipes.private')}}</small>
        <ion-checkbox v-model="form.private" slot="end"></ion-checkbox>
      </ion-item>
    </swiper-slide>
    <swiper-slide>
      <ion-item lines="none" class="ion-text-end">
        <Ion-text color="primary"><b>{{$t('recipes.add.ingredients')}} (2/3)</b></Ion-text>
        <ion-icon slot="end" :icon="chevronForward" color="primary"></ion-icon>
      </ion-item>
      <div
        class="addIngredient"
        v-for="(ingredient, k) in form.ingredients"
        :key="k"
      >
        <ion-button @click="addIngredient()">+</ion-button>
        <ion-button v-if="ingredientLength > 1" @click="removeIngredient(k)" color="tertiary">-</ion-button>
        <ion-item>
          <ion-input
            :label="$t('recipes.add.product')"
            label-placement="floating"
            type="text"
            required="true"
            v-model="ingredient.name"
          ></ion-input>
        </ion-item>
        <ion-item>
          <ion-input :label="$t('recipes.add.amount')" label-placement="floating" type="number" v-model="ingredient.amount"></ion-input>
        </ion-item>
        <ion-item>
          <ion-select :label="$t('recipes.add.unit')" label-placement="floating" v-model="ingredient.amountType" interface="popover">
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
    <swiper-slide>
      <ion-item lines="none"> 
        <ion-icon slot="start" :icon="chevronBack" color="primary" size="large"></ion-icon>
        <IonText slot="start" id="header-text" color="primary"><b>{{$t('misc.description')}} (3/3)</b></IonText>
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
      <div class="close-menu">
        <ion-button @click="saveRecipe">{{$t('misc.save')}}</ion-button>
        <ion-button color="tertiary" @click="closeMenu">{{$t('misc.cancel')}}</ion-button>
      </div>
    </swiper-slide>
  </swiper>
  </div>
  </master-layout>
</template>

<script>
import axios from "axios";
import { chevronBack, chevronForward } from "ionicons/icons";
import { Swiper, SwiperSlide} from 'swiper/vue';
import '@ionic/vue/css/ionic-swiper.css';
import 'swiper/swiper.min.css';

import {
  IonText,
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


export default{
  name: "AddRecipe",
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
  setup() {
    const onSwiper = (swiper) => {
      setTimeout(function () {
        swiper.update();
      }, 500);
    };

    return { onSwiper, chevronBack, chevronForward};
  },
  data() {
    return {
      maxCharacters: 500,
      form: {
        mode: "add",
        name: "",
        description: "",
        mealType: "diner",
        instructions: "",
        personAmount: "1",
        lang: localStorage.getItem('locale'),
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
    },
    ingredientLength: function(){
      return this.form.ingredients.length
    },
  },
  methods: {
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
        axios.post("/createrecipe", this.form)
        .then(this.resetForm)
        .then(this.$router.push({name: "recipesmenu"}))
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
    },
    closeMenu(){
      this.resetForm()
      this.$router.push({name: "recipesmenu"})
    },
    resetForm(){
      this.form = {
        mode: "add",
        name: "",
        description: "",
        mealType: "diner",
        instructions: "",
        personAmount: "1",
        lang: localStorage.getItem('locale'),
        private: false,
        ingredients: [
          {
            name: "",
            amountType: "gr",
            amount: null,
          },
        ],
      }
    }
  },
};

</script>

<style scoped>

ion-textarea{
  min-height: 40vh;
}

.swiper-slide{
  height: 70vh;
  max-width: 100vw;
  margin-top: 20px;
  border: 5px solid var(--ion-color-primary);
  border-radius: 10px;
  padding: 10px;
  overflow-x: scroll;
}

.close-menu{
  margin-bottom: 0px;
}

</style>
