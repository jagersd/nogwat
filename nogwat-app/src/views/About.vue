<template>
<master-layout pageTitle="About">
    <div class="container">
    <ion-text>
        <h4>Dank voor het uitproberen van deze app!</h4>
        <p>Het leren maken van een app is momenteel de voornaamste reden van het bestaan.</p>
        <p></p>
    </ion-text>
    </div>
</master-layout>
</template>

<script>
import { IonText } from '@ionic/vue'
import MasterLayout from '@/components/MasterLayout.vue'

export default {
    name: 'About',
    components: {IonText, MasterLayout}

}
</script>

<style scoped>
.container{
    padding: 10%;
    position: absolute;
}
</style>
