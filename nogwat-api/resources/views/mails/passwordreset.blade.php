<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Nogwat Webhome</title>

    </head>
    <body>
        <h3>Hey! </h3>
        <p>Je wachtwoord reset token:  <b> {{$resetToken}} </b>.</p>

        <p>
            Heb jij geen reset token aangevraagd maak hier zo snel mogelijk melding van via de app middels de 'Stuur feedback' mogelijkheid in de de app instellingen
            of stuur een email naar contact@nogwat-app.nl
        </p>
        <br>
    </body>
</html>