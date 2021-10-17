<template>
    <div id="mainRecipeSection">
        <ion-text>Deel je beste recepten met de rest van Nederland</ion-text>
        <ion-item>
            <ion-label position="floating">naam:</ion-label>
            <ion-input type="text" required="true" v-model="form.name"></ion-input>
        </ion-item>
        <ion-item>
            <ion-label position="floating">omschrijving:</ion-label>
            <ion-input type="text" required="true" v-model="form.description"></ion-input>
        </ion-item>
        <ion-item>
            <ion-label position="floating">maaltijd soort:</ion-label>
            <ion-select aria-placeholder="diner" v-model="form.mealType">
                <ion-select-option value="ontbijt">ontbijt</ion-select-option>
                <ion-select-option value="ontbijt">Second Breakfast</ion-select-option>
                <ion-select-option value="lunch">lunch</ion-select-option>
                <ion-select-option value="snack">snack</ion-select-option>
                <ion-select-option value="diner">diner</ion-select-option>
            </ion-select>
        </ion-item>
        <ion-item>
            <ion-label position="floating">Instructies:</ion-label>
            <ion-input type="text" required="true" v-model="form.instructions"></ion-input>
        </ion-item>
        <ion-item>
            <ion-label position="floating">Aantal personen:</ion-label>
            <ion-input type="number" required="true" v-model="form.personAmount"></ion-input>
        </ion-item>
    </div>
    <div id="ingredienListSection">
        <div class="addIngredient" v-for="(ingredient,k) in form.ingredients" :key="k">
            <ion-text>Ingrediënten</ion-text>
            <ion-item>
                <ion-label position="floating">Product</ion-label>
                <ion-input type="text" required="true" v-model="ingredient.name"></ion-input>
            </ion-item>
            <ion-item>
                <ion-label position="floating">Hoeveelheid</ion-label>
                <ion-input type="number" v-model="ingredient.amount"></ion-input>
            </ion-item>
            <ion-item>
                <ion-label position="floating">Eenheid</ion-label>
                <ion-select v-model="ingredient.amountType">
                    <ion-select-option value="el">el (eetlepel)</ion-select-option>
                    <ion-select-option value="tl">tl (theelepel)</ion-select-option>
                    <ion-select-option value="gr">gr (gram)</ion-select-option>
                    <ion-select-option value="kg">kg (kilo)</ion-select-option>
                </ion-select>
            </ion-item>
            <ion-button @click="addIngredient()">+</ion-button>
        </div>
    </div>

    <ion-button @click="saveRecipe">Opslaan</ion-button>
    <ion-button @click="closeModal">Sluit</ion-button>
</template>

<script>
import axios from 'axios'
import { IonText, modalController, IonButton, IonLabel, IonInput, IonItem, IonSelect, IonSelectOption } from '@ionic/vue'
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'AddRecipeModal',
    components: {
        IonText, IonButton, IonLabel, IonInput, IonItem, IonSelect, IonSelectOption
    },
    data(){
        return {
            form: {
                name:"",
                description:"",
                mealType:"",
                instructions:"",
                personAmount:null,
                ingredients:[
                    {
                        name:"",
                        amountType:"",
                        amount:null
                    }
                ]
            }
        }
    },
    methods: {
        addIngredient() {
            this.form.ingredients.push({
                name:"",
                amountType:"",
                amount:null,
            })
        },
        saveRecipe() {
            axios.post('/createrecipe', this.form)
            .then(this.closeModal)
            .catch(error => {
                this.errorMessage = error.message;
                console.error("This was an error!", error)
            })
        }
    },

    setup() {
        const closeModal = () => {
            modalController.dismiss()
        }
        return { closeModal }
    },

})
</script>

<style scoped>

#mainRecipeSection{
    min-height: 30vh;
    max-height: 50vh;
    overflow-x: scroll;
}

#ingredienListSection{
    min-height: 50vh;
    overflow-x: scroll;
}


#addRecipeModal{
    min-height: 100%;
}

</style>