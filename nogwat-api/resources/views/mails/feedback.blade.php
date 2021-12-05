<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Nogwat Webhome</title>

    </head>
    <body>
        <h5>Report type: {{$type}} </h5>
        <h5>Reported by: {{$userMail}}</h5>

        <p>{{$content}}</p>
    </body>
</html>
