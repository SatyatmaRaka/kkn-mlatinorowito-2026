<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo-kkn.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body.guest-layout {
                background-color: #f8f9fa;
                font-family: 'Figtree', sans-serif;
            }

            .guest-card {
                border-top: 4px solid var(--umk-blue);
            }

            .guest-logo {
                width: 8rem;
                height: 8rem;
                object-fit: contain;
                border-radius: 50%;
            }
        </style>
    </head>
    <body class="guest-layout">
        <div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
            <div class="w-100" style="max-width: 28rem;">
                <div class="text-center mb-4">
                    <a href="/">
                        <x-application-logo class="guest-logo" />
                    </a>
                </div>

                <div class="card shadow rounded-3 guest-card">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
