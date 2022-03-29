<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title>
                    ERROR
                </ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content>
            <div class="ion-text-center">
                <ion-text id="error-content">
                    <h4>Apologies, something has gone wrong</h4>
                    <p>Either the app could not connect to the backend or you currently have no internet connection.</p>
                    <p>More descriptive error messaging is on the roadmap</p>
                </ion-text>
            </div>
            <ion-button router-link="/" expand="block">
                home
            </ion-button>
        </ion-content>
    </ion-page>
</template>

<script>
import { IonPage, IonHeader, IonContent, IonToolbar, IonTitle, IonButton, IonText} from "@ionic/vue";

export default {
    name: 'Error',
    components: {IonPage,IonHeader,IonContent, IonToolbar, IonTitle, IonButton, IonText}
}
</script>

<style scoped>

ion-toolbar{
  --background: var(--ion-color-primary);
  --color: var(--ion-color-primary-contrast);
}

#error-content{
    top: 40vh;
    padding: 20px;
}

</style>