<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Nogwat Webhome</title>

    </head>
    <body>
        <h3>Hey! </h3>
        <p>{{$invitorName}} heeft je uitgenodigd voor zijn boodschappenlijst groep in de Nog Wat App.</p>

        <p>Voor meer informatie check https://nogwat.stefandejager.nl</p>
        <br>
        <p>Veel plezier en doe {{$invitorName}} de groetjes!</p>
    </body>
</html>
