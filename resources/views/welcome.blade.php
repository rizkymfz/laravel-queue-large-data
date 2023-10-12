<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('bootstrap.min.css') }}">
        
        <!-- Styles -->
        <style>
        </style>
    </head>
    <body class="antialiased">
        @component('components.header')
        @endcomponent
        <div class="container mt-5">
            <div class="form">
                <div class="jumbotron">
                    <h5>Laravel Job Batching</h5>
                    <hr>
                    <strong>Upload 5 Million records or more</strong>
                    <hr>
                    <h5>In The Background</h5>
                </div>
            </div>
        </div>
    </body>
</html>
