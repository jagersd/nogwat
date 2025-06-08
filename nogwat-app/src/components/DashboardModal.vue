<template>
    <div class="container">
        <ion-item v-for="content in messagesObject" :key="content.id" slot="content">
            <ion-text>
                <h5>{{content.title}}</h5>
                <div v-html="content.content"></div>
                <div v-html="content.content1"></div>
                <div v-html="content.content2"></div>
            </ion-text>
        </ion-item>
        <ion-button expand="block" @click="closeModal">{{$t('misc.close')}}</ion-button>
    </div>
</template>

<script>
import {
    IonItem,
    IonText,
    IonButton,
    modalController,
} from "@ionic/vue";
import { defineComponent } from "vue";

export default defineComponent({
    name: "DashboardModal",
    components: {
        IonItem,IonText, IonButton,
    },
    props:{
        messagesObject: {type: Object}
    },
    setup() {
        const closeModal = () => {
            modalController.dismiss();
        }
        return { closeModal }
    }
})
</script>

<style scoped>
.container{
    padding:1rem;
}

</style>
