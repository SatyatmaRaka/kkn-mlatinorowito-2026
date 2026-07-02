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
        <div class="px-4 py-4 border-bottom border-white border-opacity-25">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                <div class="fw-bold fs-5 lh-sm">KKN Mlatinorowito</div>
                <div class="small text-white-50">2026</div>
            </a>
        </div>

        <nav class="flex-grow-1 px-3 py-3">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a
                        href="{{ route('dashboard') }}"
                        class="admin-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.anggota.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        Anggota
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.proker.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.proker.*') ? 'active' : '' }}"
                        @click="sidebarOpen = false"
                    >
                        Program Kerja
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="admin-nav-link" @click="sidebarOpen = false">
                        Kegiatan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="admin-nav-link" @click="sidebarOpen = false">
                        Galeri
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="admin-nav-link" @click="sidebarOpen = false">
                        Pengaturan
                    </a>
                </li>
            </ul>
        </nav>

        <div class="px-4 py-3 border-top border-white border-opacity-25 mt-auto">
            <div class="small text-white-50 mb-1">{{ __('Logged in as') }}</div>
            <div class="fw-semibold mb-3 text-truncate">{{ Auth::user()->name }}</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </aside>
</div>
