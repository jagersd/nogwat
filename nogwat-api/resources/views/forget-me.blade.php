<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('favicon-16x16.png') }}">
        <title>NogWat App!</title>

    </head>
    <body>
        <div class="forget-me-container">
            <h1>User removal request</h1>
            <p>Because the NogWat App is not a big operation, user data removal is done through a manual process.</p>
            <p>Please fill out the email address of your NogWat user account in the form below.
                We will contact you for a confirmation request after which the following items will be removed:</p>
            <ul>
                <li>Your email address and all authentication elements.</li>
                <li>All NogWat App activity for groups without active users.</li>
                <li>If you are no longer in active groups, all data + app activty will be removed.</li>
            </ul>
            <form method="POST" action="{{url('forget-me')}}">
                @csrf
                <input type="text" name="email">
                <input type="submit">
            </form>

            <p>For other questions in regards to data privacy, you can find our privacy policy <a scr="https://nogwat-app.nl/privacy-policy"> here </a> , or contact us by sending an email to nogwatapp@gmail.com or by submitting a contact request through the app directly.

        </div>
    </body>

</html>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Mali&display=swap');
    body{
        background-color: #ffc2a0;
        font-family: 'Mali', cursive;
    }

    a{
        color: #1e847f;
    }

    .forget-me-container{
        margin: 2rem;
    }

    input[type=text]{
        border-radius: 10px;
    }

    input[type=submit]{
        color: snow;
        border-radius: 10px;
        background-color: #1e847f;
    }

</style>
