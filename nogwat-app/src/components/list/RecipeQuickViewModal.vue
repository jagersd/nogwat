<template>
  <div class="container">
    <ion-item lines="none">
      <ion-label>
        <h2><b>{{recipeInfo.recipe.name}}</b></h2>
      </ion-label>
    </ion-item>
    <ion-item>
      <ion-text>
        <p v-for="instruction in formattedInstructions" :key="instruction"><i>{{instruction}}. </i></p>
      </ion-text>
    </ion-item>
    <ion-label color="dark">
      <h2><b>{{$t('shoppingList.recipeViewModal.header')}}</b></h2>
    </ion-label>
    <ion-list v-for="listItem in recipeInfo.items" :key="listItem.id">
        <p class="list-item" v-if="listItem.date_purchased == null">{{listItem.item_name}}</p>
        <p class="list-item" v-if="listItem.date_purchased != null"><s>{{listItem.item_name}}</s></p>
    </ion-list>
    <ion-button @click="closeModal" expand="block">{{$t('misc.close')}}</ion-button>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import axios from 'axios';
import {
 IonButton, modalController, IonLabel, IonText, IonItem, IonList
} from "@ionic/vue";

export default defineComponent({
  name: 'RecipeQuickViewModal',
  components: {IonButton, IonLabel, IonText, IonItem, IonList},
  props: ['groupId', 'recipeId'],

  async created(){
    axios.get(`/addedRecipeDetails/${this.groupId}/${this.recipeId}`)
    .then(response => (this.recipeInfo = response.data))
    .catch(error => console.log(error))
  },
  setup() {
    const closeModal = () => {
    modalController.dismiss();
    }
    return { closeModal}
  },

  data(){
    return{
      recipeInfo : {
        recipe: {
          instructions: undefined
        }
      }
    }
  },

  computed:{
    formattedInstructions: function(){
      return this.recipeInfo.recipe.instructions ? this.recipeInfo.recipe.instructions.split(". ") : null
    }
  }
})
</script>

<style scoped>

.container{
  padding-left: 1rem;
  padding-right: 1rem;
  overflow-x: scroll;
}

.list-item{
  margin: 0;
  color: var(--ion-color-secondary);
}

</style>