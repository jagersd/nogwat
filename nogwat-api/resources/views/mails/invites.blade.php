<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Nogwat Webhome</title>

    </head>
    <body>
        <h3>Hey! </h3>
        <p>{{$invitorName}} heeft je uitgenodigd voor een boodschappenlijst groep in de NogWat-App.</p>

        <p>Voor meer informatie kijk op https://nogwat-app.nl/</p>
        <br>
        <p>Veel plezier en doe {{$invitorName}} de groetjes!</p>
    </body>
</html>
