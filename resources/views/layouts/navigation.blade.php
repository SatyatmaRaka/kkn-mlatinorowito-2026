<div x-data="{ sidebarOpen: false }">
    <!-- Mobile top bar -->
    <div class="d-lg-none position-fixed top-0 start-0 end-0 bg-white border-bottom shadow-sm d-flex align-items-center px-3 py-2 admin-mobile-topbar">
        <button type="button" class="btn btn-link text-dark p-0 border-0" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>
        <span class="ms-2 fw-semibold text-truncate">KKN Mlatinorowito 2026</span>
    </div>

    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="d-lg-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" style="z-index: 1035;"></div>

    <aside class="admin-sidebar text-white d-flex flex-column" :class="{ 'sidebar-mobile-open': sidebarOpen }">
        <div class="px-4 py-4 border-bottom border-white border-opacity-25 d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo-kkn.png') }}" alt="Logo KKN Mlatinorowito 2026" class="rounded-circle" style="width: 45px; height: 45px; object-fit: contain;">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                <div class="fw-bold fs-6 lh-sm">KKN Mlatinorowito</div>
                <div class="small text-white-50">2026</div>
            </a>
        </div>

        <nav class="flex-grow-1 px-3 py-4">
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="sidebarOpen = false">
                        <i class="bi bi-grid-1x2-fill fs-5"></i><span>Dasbor</span>
                    </a>
                </li>

                <li class="nav-item mt-2 mb-1"><span class="text-white-50 small fw-bold px-3 text-uppercase">KKN Operasional</span></li>
                <li class="nav-item">
                    <a href="{{ route('panel.catatan-harian.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.catatan-harian.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                        <i class="bi bi-journal-text fs-5"></i><span>Logbook</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('panel.absensi.riwayat') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.absensi.riwayat') ? 'active' : '' }}" @click="sidebarOpen = false">
                        <i class="bi bi-clock-history fs-5"></i><span>Absensi</span>
                    </a>
                </li>
                @if (Auth::user()->canCheckInAbsensi())
                    <li class="nav-item">
                        <a href="{{ route('absensi.scan') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('absensi.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-qr-code-scan fs-5"></i><span>Scan Absensi</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('panel.buku-tamu.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.buku-tamu.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                        <i class="bi bi-book fs-5"></i><span>Buku Tamu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('panel.kegiatan-pelaksanaan.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.kegiatan-pelaksanaan.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                        <i class="bi bi-calendar-event fs-5"></i><span>Kegiatan Pelaksanaan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('panel.observasi-lapangan.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.observasi-lapangan.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                        <i class="bi bi-binoculars fs-5"></i><span>Observasi Lapangan</span>
                    </a>
                </li>
                @if (Auth::user()->canReviewLogbook())
                    <li class="nav-item">
                        <a href="{{ route('panel.absensi.rekap') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.absensi.rekap') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-clipboard-check fs-5"></i><span>Rekap Absensi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('panel.laporan.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.laporan.index', 'panel.laporan.cetak', 'panel.laporan.export') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-file-earmark-bar-graph fs-5"></i><span>Laporan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('panel.laporan.daftar-hadir-mingguan') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.laporan.daftar-hadir-mingguan*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-calendar-week fs-5"></i><span>Daftar Hadir Mingguan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('panel.laporan.logbook-harian') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.laporan.logbook-harian*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-journal-check fs-5"></i><span>Logbook Harian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('panel.laporan.rekap-keaktifan') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.laporan.rekap-keaktifan*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-bar-chart-line fs-5"></i><span>Rekap Keaktifan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('panel.absensi.qr') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.absensi.qr') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-printer fs-5"></i><span>Cetak QR</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->canManageKeuangan() && ! Auth::user()->canReviewLogbook())
                    <li class="nav-item mt-2 mb-1"><span class="text-white-50 small fw-bold px-3 text-uppercase">Laporan</span></li>
                    <li class="nav-item">
                        <a href="{{ route('panel.laporan.keuangan') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.laporan.keuangan') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-file-earmark-bar-graph fs-5"></i><span>Laporan Keuangan</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->canManageKeuangan())
                    <li class="nav-item mt-2 mb-1"><span class="text-white-50 small fw-bold px-3 text-uppercase">Keuangan</span></li>
                    <li class="nav-item">
                        <a href="{{ route('panel.keuangan.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.keuangan.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-wallet2 fs-5"></i><span>Manajemen Keuangan</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->canManageWebsiteKonten())
                    <li class="nav-item mt-2 mb-1"><span class="text-white-50 small fw-bold px-3 text-uppercase">Manajemen Website</span></li>
                    <li class="nav-item">
                        <a href="{{ route('panel.anggota.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.anggota.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-people-fill fs-5"></i><span>Anggota</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('panel.program-kerja.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.program-kerja.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-kanban-fill fs-5"></i><span>Program Kerja</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('panel.ukm.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.ukm.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-shop fs-5"></i><span>Pemetaan UKM</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2 mb-1"><span class="text-white-50 small fw-bold px-3 text-uppercase">Sistem</span></li>
                    <li class="nav-item">
                        <a href="{{ route('panel.pengaturan.index') }}" class="admin-nav-link d-flex align-items-center gap-3 {{ request()->routeIs('panel.pengaturan.*') ? 'active' : '' }}" @click="sidebarOpen = false">
                            <i class="bi bi-gear-fill fs-5"></i><span>Pengaturan</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="px-4 py-4 border-top border-white border-opacity-25 mt-auto">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill fs-4 text-white"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="small text-white-50 lh-1 mb-1">{{ Auth::user()->anggota?->jabatan ?? Auth::user()->role->label() }}</div>
                    <div class="fw-semibold text-truncate lh-1">{{ Auth::user()->name }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100">Keluar</button>
            </form>
        </div>
    </aside>
</div>
