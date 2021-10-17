<template>
    <master-layout pageTitle="Recepten">
        <ion-text>Wat eten we vanavond?</ion-text>
        <ion-list>
        <ion-button expand="block">Mijn Recepten</ion-button>
        <ion-button expand="block">Mijn Favoriete Recepten</ion-button>
        <ion-button expand="block">Alle Recepten</ion-button>
        <ion-button class="ion-margin-top" @click="openAddRecipeModal()">+ voeg recept toe</ion-button>
        </ion-list>
    </master-layout>
</template>

<script>
import { IonButton, IonText, IonList, modalController} from '@ionic/vue'
import AddRecipeModal from '../components/recipes/AddRecipeModal.vue'


export default {
    name: 'Recipes',
    components: {
        IonButton, IonText,IonList
    },
    methods:{
        async openAddRecipeModal() {
            const modal = await modalController.create({
                component:AddRecipeModal,
                cssClass:"addRecipeModal",
            })
            return modal.present()
        }
        
    }
    
}
</script>