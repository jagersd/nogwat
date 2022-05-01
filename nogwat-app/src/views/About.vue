<template>
  <master-layout pageTitle="About">
    <div class="container" v-if="$i18n.locale == 'en'">
      <ion-text class="header">
        <h1>Welcome to the NogWat app Alpha test build!</h1>
      </ion-text>
      <div class="orange">
        <ion-text>
          <h4>Just imagine:</h4>
          <p>You quickly pick up some stuff the neighbourhood shops. It is a busy day and need to visit the gym after work as well...
            </p>
            <p>Just popping into the supermarket because the peanut butter ran out and you remember the tea biscuits at the office cantine were gone too last time. 
            </p>
            <p>As you are here anyways, might as well get some other stuff, but it is pretty touch to remember what the family needed. 
            </p>
            <p>After a view short rounds across the supermarket isles you have filled your cart with some random stuff. Afterwards a quick fly-by to the local pharmacy because you needed those blister bandages.
            </p>
            <p>
                After arriving at home 30 minutes after you had planned, it turns out someone else had already gone to the supermarket and thought of the same thing for diner as you did. <br>
                ah and if you where at the pharmacy anyways you could have picked up those new toothbrushes the kids needed....
            </p>
        </ion-text>
      </div>
      <div class="primary">
      <ion-text>
        <h4>Sounds familiar?</h4>
          <p>If so, there could be an App available in the near future which does not make your live easier but might assist in your day to day rush.</p>
          <h4>What could I do with it?</h4>
          <ul>
              <li>Create groups for people with which you would like to have one and the same shopping list.</li>
              <li>Add products to the shopping list and mark them as 'bought'.</li>
              <li>Store your day-to-day diner recipes so you can easily add all items to the list.</li>
              <li>Browse through recipes from other users for inspiration.</li>
              <li>Other things like that but always in a convenient / simple manner.</li>
          </ul>
          <h4>What is the App not going to do?</h4>
          <ul>
              <li>Send your shopping list directly to a delivery service.</li>
              <li>Be opinionated in regards to where to get the items on your list.</li>
              <li>Share data on shopping habbits with 3rd parties.</li>
          </ul>
      </ion-text>
      </div>
      <ion-text>
        <h4>Good to know prior to participation:</h4>
        <p>First and foremost, it is great that you are considering using such an application!</p>
        <p>Currently the App finds itself in an Alpha testing stage. That us why a few things should be kept in consideration</p>
        <ul>
          <li>bugs bugs bugs are a near certainty</li>
          <li>Missende Features (like not being able to adjust and delete a recipe)</li>
          <li>Translation to English is not completed. In app notifications and email are still exclusively Dutch.</li>
          <li>Currently the App runs in the browser and a closed beta through the Android Playstore.</li>
          <li>There could be a time where the app needs to completely regenerate its backend meaning that all data would be lost. In case this is nessecary users will be informed upfront</li>
        </ul>
        <i>version id: {{frontEndVersion}}</i>
      </ion-text>
    </div>
    <div v-else class="container">
      <ion-text class="header">
        <h1>Welkom bij de alpha van de "Nogwat" App!</h1>
      </ion-text>
      <div class="orange">
        <ion-text>
          <h4>Stel je voor:</h4>
          <p>Je bent even bij het winkelcentrum in de buurt, het is een drukke dag en je wilt na werk nog richting de sportschool...
            </p>
            <p>Even de supermarkt in want je zou nog een pot pindakaas ophalen voor thuis en 'oh ja!' de rijstwafels in de keuken op werk zijn op!
            </p>
            <p>Nu je er toch bent, kun je net zo goed ook wat andere dingen meenemen. Maar waar wat hadden we ook weer op dat krijtbord geschreven??
            </p>
            <p>Na een paar keer de supermarkt rond te hebben gelopen en uiteindelijk op de gok het mandje met een aantal zaken te hebben gevuld, schiet je nog even bij de drogist naarbinnen voor lensenvloeistof.
            </p>
            <p>
                Nadat je een half uur later thuis bent gekomen dan gepland blijkt dat er al avondeten in huis was en het dit voor hetzelfde recept als jij bij elkaar had geimproviseerd in de supermarkt. <br>
                oh en je had ook gelijk die nieuwe tandenborsels mee kunnen nemen als je toch naar de drogist was....
            </p>
        </ion-text>
      </div>
      <div class="primary">
      <ion-text>
        <h4>Klinkt bekend?</h4>
          <p>Dan zou er binnenkort een eenvoudige app kunnen zijn die niet het leven, maar wel de dag wat eenvoudiger maken kan.</p>
          <h4>Wat kan ik ermee?</h4>
          <ul>
              <li>Groepen aanmaken met wie je een gezamelijke boodschappenlijst bij wilt houden.</li>
              <li>Toevoegen en afvinken van producten op je boodschappenlijstje.</li>
              <li>Je eigen recepten opslaan zodat je deze eenvoudig nog eens op de lijst kunt zetten.</li>
              <li>Blader door recepten van andere gebruikers zodat je zelf niet altijd met inspiratie hoeft te komen.</li>
              <li>En dingen in het verlengde van bovenstaande maar vooral zo eenvoudig mogelijk.</li>
          </ul>
          <h4>Maar wat gaat het niet doen?</h4>
          <ul>
              <li>Je boodschappenlijst rechtstreeks doorsturen naar een bezorgservice.</li>
              <li>Beoordelen waar jij je boodschappen zou moeten halen.</li>
              <li>Data over jouw boodschappengedrag en voorkeuren doorspelen naar derden.</li>
          </ul>
      </ion-text>
      </div>
      <ion-text>
        <h4>Goed te weten voor aanmelding:</h4>
        <p>Allereerst, heel erg leuk dat je overweegt gebruik te maken van deze applicatie!</p>
        <p>Momenteel bevind de applicatie zich in een soort 'Alpha' status. Er zijn daarom een paar dingen om rekening mee te houden</p>
        <ul>
          <li>Potentiele bugs bugs bugs</li>
          <li>Missende Features (zoals het niet kunnen aanpassen of verwijderen van recepten)</li>
          <li>Meertaligheid is nog niet compleet</li>
          <li>De app werkt momenteel via de browser of een gesloten beta test in de Android Playstore.</li>
          <li>In uiterste noodzaak kan het zo zijn dat de database compleet opnieuw opgebouwd moet worden waardoor alle informatie (gebruikers, groepen, recepten, etc) verloren gaat. Mocht dit nodig zijn zullen gebruikers hier vooraf van op de hoogte worden gebracht.</li>
        </ul>
        <i>versie id: {{frontEndVersion}}</i>
      </ion-text>
    </div>

  </master-layout>
</template>

<script>
import { IonText } from "@ionic/vue";
import MasterLayout from "@/components/MasterLayout.vue";

export default {
  name: "About",
  components: { MasterLayout, IonText },
  data() {
    return {
      frontEndVersion : 'Alpha - 22.05.1'
    }
  }
};
</script>

<style scoped>
.container {
  padding-left: 15px;
  padding-right: 15px;
}

p,h4{
  padding-left: 5px;
  padding-right: 5px;
}

.orange{
  background-color: #d5865c;
  border: 3px solid black;
  border-radius: 5px;
  box-shadow: 4px 2px 15px 0px rgba(0,0,0,0.6);
}

.primary{
  margin-top: 10px;
  background-color: #1e847f;
  border: 3px solid black;
  border-radius: 5px;
  color: #ffc2a0;
  box-shadow: 4px 2px 15px 0px rgba(0,0,0,0.6);
}
</style>
