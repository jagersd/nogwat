<template>
    <main-layout>
        <div class="container" id="header">
            <div class="row justify-content-center">
                <h1>Welkom bij de "Nogwat" website!</h1>
            </div>
        </div>
        <div class="container">

            <div class="row main-text">
                <h4>Stel je voor! </h4>
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
            </div>
            <div class="row second-text">
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
                    <li>Je boodschappenlijst rechtstreeks doorzetten naar een bezorgservice.</li>
                    <li>Beoordelen waar jij je boodschappen zou moeten halen.</li>
                    <li>Data over jouw boodschappengedrag en voorkeuren doorspelen naar derden.</li>
                </ul>
            </div>

            <div class="row good-to-know">
                <h4>Goed te weten voor installatie / aanmelding:</h4>
                <p>Allereerst, leuk dat je overweegt gebruik te maken van de NogWat App!</p>
                <p>De applicatie bevind zich in een Alpha status. Daarom zijn er een paar dingen om rekening mee te houden.</p>
                <ul>
                    <li>Potentiele bug bugs bugs.</li>
                    <li>Additionele features zullen nog geintroduceerd worden.</li>
                    <li>Meertaligheid nog niet compleet.</li>
                    <li>De Android app is uitsluitend in besloten test status beschikbaar bij de Playstore.</li>
                    <li>Voor IOS gebruikers kan Safari gebruikt worden door <a href="https://web.nogwat-app.nl">hier</a> (https://web.nogwat-app.nl) in te loggen.</li>
                    <li>In uiterste noodzaak zal de database compleet leeggehaald moeten worden waardoor alle data verloren gaat. Mocht dit nodig zijn zullen gebruikers hiervan vooraf op de hoogte worden gebracht.</li>
                </ul>
            </div>

            <div id="logo-image">
                <img src="/images/nogwat-logo.png" alt="">
            </div>

            <div class="row tech-section">
                <div class="col-12">
                <h4>Benieuwd naar ontwikkelingen?</h4>
                <p>De app wordt gebouwd uit liefhebberij en leergierigheid voor web development en is momenteel een 1-persoons project dus het kan een lang duren voordat het openbaar in de Apple Appstore en de Android Playstore te vinden is.</p>
                <i>Technische details voor de geinteresseerden: </i>
                <p>Volledige code en voortgang is <a href="https://github.com/jagersd/nogwat" target="_blank">hier</a> in te zien.</p>
                <p>De backend betreft een PHP/Laravel API. <br>
                En de App zelf is een Ionic project met het VueJS framework.</p>
                </div>
            </div>
        </div>

    </main-layout>
</template>

<script>
export default {
    name: 'home'
}
</script>

<style scoped>
    #header{
        background-color: #1e847f;
        border: 5px solid black;
        box-shadow: 4px 2px 15px 0px rgba(0,0,0,0.6);
        border-radius: 5px;
        margin-top: 1rem;
        max-width: 80vw;
    }

    #logo-image{
        text-align: center;
        margin-top: 3rem;
    }

    #logo-image img{
        width: 10vw;
    }

    @media only screen and (max-width: 600px){
        #header h1{
            font-size: 25px;
        }
        
        .container{
            font-size: 18px;
            padding-left: 10vw;
            padding-right: 10vw;
        }

        .tech-section p{
            font-size: 16px;
        }
        .tech-section i{
            font-size: 16px;
        }
    }

    .main-text{
        margin-top: 2rem;
        background-color: #d5865c;
        padding: 2rem;
        border: 5px solid black;
        border-radius: 5px;
        box-shadow: 4px 2px 15px 0px rgba(0,0,0,0.6);
    }

    .second-text{
        margin-top: 3rem;
        background-color: #1e847f;
        padding: 1rem;
        border: 5px solid black;
        border-radius: 5px;
        color: #ecc19c;
        box-shadow: 4px 2px 15px 0px rgba(0,0,0,0.6);
    }

    .good-to-know{
        margin-top: 2rem;
    }

    .tech-section{
        margin-top: 2rem;
        font-size: 18px;
        padding-bottom: 5vh;
        text-align: center;
    }
</style>
