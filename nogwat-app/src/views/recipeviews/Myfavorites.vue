<template>
    <master-layout pageTitle="Mijn Favoriete Recepten">
        <ion-list v-for="myFavo in myFavos" :key="myFavo.id">
            <ion-item @click="openDetailRecipeModal(myFavo)">
                <ion-label><b>{{myFavo.name}}</b><br>
                <i>{{myFavo.description}}</i>
                </ion-label>
            </ion-item>
        </ion-list>
    </master-layout>
</template>

<script>
import {IonList, IonItem, IonLabel, modalController} from '@ionic/vue'
import axios from 'axios'
import detailRecipeModal from '@/components/recipes/detailRecipeModal.vue'

export default {
    name: 'Myfavorites',
    components: {IonList, IonItem, IonLabel},
    data() {
        return{
            myFavos: {},
        }
    },
    async ionViewWillEnter() {
        this.getFavorites()
    },
    methods: {
        async getFavorites(){
            axios.get('/myfavorites')
            .then(response => (this.myFavos = response.data))
            .catch(error=>console.log(error))
        },
        async openDetailRecipeModal(recipe) {
            const modal = await modalController.create({
                component: detailRecipeModal,
                componentProps: {
                    recipeDetails: recipe
                }
            })
            modal.onDidDismiss().then(() => {
            this.getFavorites();
            });
            return modal.present();
        }
    }
}
</script>
