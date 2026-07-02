<nav
    x-data="{ mobileOpen: false, scrolled: false }"
    @scroll.window="scrolled = (window.scrollY > 50)"
    class="navbar navbar-expand-lg fixed-top bg-white py-3"
    :class="{ 'shadow': scrolled, 'shadow-none': !scrolled }"
    style="transition: box-shadow 0.3s ease;"
>
    <style>
        .public-nav-link {
            color: var(--umk-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .public-nav-link:hover,
        .public-nav-link:focus {
            color: var(--umk-yellow);
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .public-navbar-brand {
            color: var(--umk-blue);
            font-weight: 700;
            text-decoration: none;
        }

        .public-navbar-brand:hover {
            color: var(--umk-blue);
        }

        .public-nav-toggle {
            color: var(--umk-blue);
            border-color: rgba(0, 51, 102, 0.25);
        }

        .public-mobile-menu {
            background-color: #fff;
            border-top: 1px solid rgba(0, 51, 102, 0.1);
        }
    </style>

    <div class="container px-3 px-md-4">
        <div class="d-flex align-items-center justify-content-between w-100">
            <a class="public-navbar-brand fs-5 fs-md-4" href="#beranda" @click="mobileOpen = false">
                KKN Mlatinorowito 2026
            </a>

            <button
                type="button"
                class="btn public-nav-toggle d-lg-none"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen"
                aria-label="Toggle navigation"
            >
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>

            <div class="d-none d-lg-flex align-items-center gap-4">
                <a class="public-nav-link" href="#beranda">Beranda</a>
                <a class="public-nav-link" href="#tentang">Tentang</a>
                <a class="public-nav-link" href="#anggota">Anggota</a>
                <a class="public-nav-link" href="#proker">Program Kerja</a>
                <a class="public-nav-link" href="#kegiatan">Kegiatan</a>
                <a class="public-nav-link" href="#galeri">Galeri</a>
                <a class="public-nav-link" href="#kontak">Kontak</a>
            </div>
        </div>

        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="public-mobile-menu d-lg-none mt-3 pt-3"
            x-cloak
        >
            <div class="d-flex flex-column gap-3 pb-2">
                <a class="public-nav-link" href="#beranda" @click="mobileOpen = false">Beranda</a>
                <a class="public-nav-link" href="#tentang" @click="mobileOpen = false">Tentang</a>
                <a class="public-nav-link" href="#anggota" @click="mobileOpen = false">Anggota</a>
                <a class="public-nav-link" href="#proker" @click="mobileOpen = false">Program Kerja</a>
                <a class="public-nav-link" href="#kegiatan" @click="mobileOpen = false">Kegiatan</a>
                <a class="public-nav-link" href="#galeri" @click="mobileOpen = false">Galeri</a>
                <a class="public-nav-link" href="#kontak" @click="mobileOpen = false">Kontak</a>
            </div>
        </div>
    </div>
</nav>
