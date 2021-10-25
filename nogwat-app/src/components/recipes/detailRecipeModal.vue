<template>
    <ion-card color="primary">
        <ion-card-header>
            <ion-card-title>{{recipeDetails.name}}</ion-card-title>
            <ion-card-subtitle>Gedeeld door: {{recipeDetails.user.name}}</ion-card-subtitle>
        </ion-card-header>
        <ion-card-content>

            <i>{{recipeDetails.description}}</i>
            <h2 id="ingredient-header">Ingredienten voor {{recipeDetails.person_amount}} personen</h2>

            <ion-range min="1" max="10" step="1" :value="recipeDetails.person_amount" snaps color="dark">
                <ion-icon slot="start" size="small" :icon="person"></ion-icon>
                <ion-icon slot="end" size="small" :icon="people"></ion-icon>
            </ion-range>

            <ion-list class="ingredient-list" inset="true" v-for="ingredient in recipeDetails.recipe_items" :key="ingredient.id">
                {{ingredient.measurement_amount}} {{ingredient.measurement.full_name}} {{ingredient.item_name}}
            </ion-list>    
        </ion-card-content>
    </ion-card>
    <ion-text color="secondary">
    <p>{{recipeDetails.instructions}}</p>
    </ion-text>

    <ion-button expand="block" @click="closeModal">Voeg toe aan lijst</ion-button>
    <ion-button expand="block" @click="closeModal">Sluit</ion-button>
</template>

<script>

import { defineComponent } from 'vue'
import { modalController, IonButton, IonCard, IonCardHeader, IonCardSubtitle, IonCardTitle, IonCardContent, IonList, IonRange, IonText, IonIcon} from '@ionic/vue'
import { person, people} from "ionicons/icons";

export default defineComponent({
    name: 'detailRecipeModal',
    components: {IonButton, IonCard, IonCardHeader, IonCardSubtitle, IonCardTitle, IonCardContent, IonList, IonRange, IonText, IonIcon},
    props: ['recipeDetails'],
    setup() {
        const closeModal = () => {
            modalController.dismiss()
        }
        return { closeModal, person, people }
    },

})
</script>

<style scoped>
.ingredient-list{
    color: black;
    padding-left: 1rem;
}

#ingredient-header{
    margin-top: 10px;
}

ion-text{
    padding: 2rem;
}
</style>