<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')

        <style>
            body.admin-layout {
                font-family: 'Figtree', sans-serif;
                background-color: #f8f9fa;
            }

            .admin-sidebar {
                width: 250px;
                background-color: var(--umk-blue);
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                overflow-y: auto;
                z-index: 1040;
                transition: transform 0.25s ease;
            }

            .admin-nav-link {
                display: block;
                padding: 0.65rem 1rem;
                color: rgba(255, 255, 255, 0.85);
                text-decoration: none;
                border-radius: 0.375rem;
                transition: background-color 0.15s ease, color 0.15s ease;
            }

            .admin-nav-link:hover {
                background-color: rgba(255, 255, 255, 0.12);
                color: #fff;
            }

            .admin-nav-link.active {
                background-color: rgba(255, 255, 255, 0.2);
                color: #fff;
                font-weight: 600;
            }

            .admin-main {
                min-height: 100vh;
            }

            .admin-mobile-topbar {
                z-index: 1030;
                height: 56px;
            }

            @media (max-width: 991.98px) {
                .admin-sidebar {
                    transform: translateX(-100%);
                }

                .admin-sidebar.sidebar-mobile-open {
                    transform: translateX(0);
                }

                .admin-main {
                    padding-top: 56px;
                }
            }

            @media (min-width: 992px) {
                .admin-main {
                    margin-left: 250px;
                }
            }
        </style>
    </head>
    <body class="admin-layout">
        @include('layouts.navigation')

        <div class="admin-main">
            <div class="container-fluid p-4">
                @isset($header)
                    <header class="mb-4">
                        <div class="mb-0 fw-semibold fs-4">{{ $header }}</div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
