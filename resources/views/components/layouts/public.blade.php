<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pageTitle = $title ?? 'KKN Mlatinorowito 2026';
            $pageDescription = $description ?? 'Website Resmi KKN UMK Kelompok Desa Mlatinorowito tahun 2026. Temukan info program kerja, anggota tim, dan informasi kelompok KKN.';
            $pageImage = $image ?? asset('images/logo-kkn.png');
            $pageUrl = $url ?? url()->current();
        @endphp

        <title>{{ $pageTitle }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="canonical" href="{{ $pageUrl }}">

        <meta name="description" content="{{ $pageDescription }}">
        <meta name="keywords" content="KKN, UMK, Mlatinorowito, Kudus, Universitas Muria Kudus, KKN 2026">
        <meta name="robots" content="index, follow">

        <meta property="og:type" content="website">
        <meta property="og:locale" content="id_ID">
        <meta property="og:site_name" content="KKN Mlatinorowito 2026">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:url" content="{{ $pageUrl }}">
        <meta property="og:image" content="{{ $pageImage }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
        <meta name="twitter:image" content="{{ $pageImage }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

        <style>
            html {
                scroll-behavior: smooth;
            }

            body.public-layout {
                font-family: 'Outfit', system-ui, -apple-system, sans-serif;
                background-color: var(--bs-body-bg);
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
