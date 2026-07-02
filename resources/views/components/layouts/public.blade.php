<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'KKN Mlatinorowito 2026' }}</title>
        <meta name="description" content="{{ $description ?? 'Website Resmi KKN UMK Kelompok Desa Mlatinorowito tahun 2026. Temukan info program kerja, anggota tim, kegiatan harian, dan galeri dokumentasi kami.' }}">
        <meta name="keywords" content="KKN, UMK, Mlatinorowito, Kudus, Universitas Muria Kudus, KKN 2026">

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
