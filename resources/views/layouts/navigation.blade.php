<div x-data="{ sidebarOpen: false }">
    <!-- Mobile top bar -->
    <div class="d-lg-none position-fixed top-0 start-0 end-0 bg-white border-bottom shadow-sm d-flex align-items-center px-3 py-2 admin-mobile-topbar">
        <button
            type="button"
            class="btn btn-link text-dark p-0 border-0"
            @click="sidebarOpen = !sidebarOpen"
            aria-label="Toggle navigation"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>
        <span class="ms-2 fw-semibold text-truncate">KKN Mlatinorowito 2026</span>
    </div>

    <!-- Mobile backdrop -->
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="d-lg-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"
        style="z-index: 1035;"
    ></div>

    <!-- Sidebar -->
    <aside
        class="admin-sidebar text-white d-flex flex-column"
        :class="{ 'sidebar-mobile-open': sidebarOpen }"
    >
        <div class="px-4 py-4 border-bottom border-white border-opacity-25 d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo KKN" class="rounded-circle bg-white p-1" style="width: 45px; height: 45px; object-fit: contain;">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                <div class="fw-bold fs-6 lh-sm">KKN Mlatinorowito</div>
                <div class="small text-white-50">2026</div>
            </a>
        </div>

        <nav class="flex-grow-1 px-3 py-4">
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a
                        href="{{ route('dashboard') }}"
                        class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <i class="bi bi-grid-1x2-fill fs-5"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item mt-2 mb-1">
                    <span class="text-white-50 small fw-bold px-3 text-uppercase tracking-wider">Manajemen</span>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.anggota.index') }}"
                        class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <i class="bi bi-people-fill fs-5"></i>
                        <span>Anggota</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.proker.index') }}"
                        class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('admin.proker.*') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <i class="bi bi-kanban-fill fs-5"></i>
                        <span>Program Kerja</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.kegiatan.index') }}"
                        class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('admin.kegiatan.*') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <i class="bi bi-calendar-event-fill fs-5"></i>
                        <span>Kegiatan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.galeri.index') }}"
                        class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <i class="bi bi-images fs-5"></i>
                        <span>Galeri</span>
                    </a>
                </li>
                <li class="nav-item mt-2 mb-1">
                    <span class="text-white-50 small fw-bold px-3 text-uppercase tracking-wider">Sistem</span>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.pengaturan.index') }}"
                        class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        <i class="bi bi-gear-fill fs-5"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="px-4 py-4 border-top border-white border-opacity-25 mt-auto">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill fs-4 text-white"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="small text-white-50 lh-1 mb-1">{{ __('Logged in as') }}</div>
                    <div class="fw-semibold text-truncate lh-1">{{ Auth::user()->name }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </aside>
</div>
