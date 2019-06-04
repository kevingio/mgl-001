<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Aplikasi manajemen inventaris berbasis web">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="theme-color" content="#DB0000">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Stockist Magelang Agency</title>
        <link rel="icon" href="favicon.png">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <style media="screen">
            html, body {
                min-height: 100%;
            }
            #offline {
                height: 100%;
            }
        </style>
    </head>
    <body>
        <div id="offline">
            <app-offline></app-offline>
        </div>
        <script src="{{ mix('/js/app.js') }}" charset="utf-8"></script>
    </body>
</html>
