<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>KKN Mlatinorowito 2026</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

        <style>
            html {
                scroll-behavior: smooth;
            }

            body.public-layout {
                font-family: 'Poppins', sans-serif;
            }
        </style>
    </head>
    <body class="public-layout">
        @include('components.navbar')

        <main class="public-main">
            {{ $slot }}
        </main>

        @include('components.footer')

        @stack('scripts')
    </body>
</html>
