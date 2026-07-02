<div class="fixed-top pt-3 px-3 px-md-4" style="z-index: 1030; pointer-events: none;">
    <nav
        x-data="{ mobileOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.scrollY > 20)"
        class="navbar navbar-expand-lg rounded-pill mx-auto"
        :class="{ 'glass-nav shadow-lg': scrolled, 'bg-white shadow-sm': !scrolled }"
        style="transition: all 0.4s ease; max-width: 1140px; pointer-events: auto; padding: 0.5rem 1.25rem;"
    >
        <style>
            .public-nav-link {
                color: var(--umk-blue);
                text-decoration: none;
                font-weight: 500;
                padding: 0.5rem 1rem;
                border-radius: 50rem;
                transition: all 0.3s ease;
            }

            .public-nav-link:hover,
            .public-nav-link:focus {
                color: var(--umk-blue-accent);
                background: rgba(59, 130, 246, 0.08);
            }

            .public-navbar-brand img {
                height: 44px;
                width: 44px;
                object-fit: cover;
                border-radius: 50%;
                transition: transform 0.3s ease;
            }
            .public-navbar-brand:hover img {
                transform: scale(1.05);
            }

            .public-nav-toggle {
                color: var(--umk-blue);
                border: none;
                background: rgba(15, 23, 42, 0.05);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }
            .public-nav-toggle:hover {
                background: rgba(15, 23, 42, 0.1);
            }

            .public-mobile-menu {
                background-color: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-radius: 1.25rem;
                box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15);
                position: absolute;
                top: calc(100% + 10px);
                left: 0;
                right: 0;
                padding: 1rem;
                border: 1px solid rgba(0,0,0,0.05);
            }
        </style>

        <div class="d-flex align-items-center justify-content-between w-100 position-relative">
            <a class="public-navbar-brand d-flex align-items-center" href="/#beranda" @click="mobileOpen = false">
                <img src="{{ asset('images/logo.png') }}" alt="Logo KKN UMK 2026">
            </a>

            <button
                type="button"
                class="btn public-nav-toggle d-lg-none p-0"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen"
                aria-label="Toggle navigation"
            >
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>

            <div class="d-none d-lg-flex align-items-center gap-1">
                <a class="public-nav-link" href="/#beranda">Beranda</a>
                <a class="public-nav-link" href="/#tentang">Tentang</a>
                <a class="public-nav-link" href="/#anggota">Anggota</a>
                <a class="public-nav-link" href="/#kegiatan-galeri">Kegiatan & Galeri</a>
                @auth
                    <a class="public-nav-link" href="{{ route('dashboard') }}" style="color: var(--umk-blue-accent);">Dashboard</a>
                @else
                    <a class="public-nav-link" href="{{ route('login') }}" style="color: var(--umk-blue-accent);">Login</a>
                @endauth
                <a class="btn btn-primary btn-sm ms-2 px-4 rounded-pill" href="/#kontak" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">Kontak</a>
            </div>

            <!-- Mobile Menu -->
            <div
                x-show="mobileOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                class="public-mobile-menu d-lg-none"
                x-cloak
            >
                <div class="d-flex flex-column gap-1">
                    <a class="public-nav-link" href="/#beranda" @click="mobileOpen = false">Beranda</a>
                    <a class="public-nav-link" href="/#tentang" @click="mobileOpen = false">Tentang</a>
                    <a class="public-nav-link" href="/#anggota" @click="mobileOpen = false">Anggota</a>
                    <a class="public-nav-link" href="/#proker" @click="mobileOpen = false">Program Kerja</a>
                    <a class="public-nav-link" href="/#kegiatan-galeri" @click="mobileOpen = false">Kegiatan & Galeri</a>
                    <hr class="my-1 border-secondary opacity-25">
                    @auth
                        <a class="public-nav-link text-primary fw-semibold" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="public-nav-link text-primary fw-semibold" href="{{ route('login') }}">Login</a>
                    @endauth
                    <a class="btn btn-primary mt-2 rounded-pill w-100" href="/#kontak" @click="mobileOpen = false">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </nav>
</div>
