<template>
    {{recipeDetails}}
    <ion-button @click="closeModal">Sluit</ion-button>
</template>

<script>

import { defineComponent } from 'vue'
import { modalController, IonButton, } from '@ionic/vue'


export default defineComponent({
    name: 'detailRecipeModal',
    components: {IonButton},
    props: ['recipeDetails'],
    setup() {
        const closeModal = () => {
            modalController.dismiss()
        }
        return { closeModal }
    },

})
</script>