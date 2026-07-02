<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard Overview') }}
    </x-slot>

    <!-- Welcome Banner -->
    <div class="premium-card mb-5 border-0" style="background: linear-gradient(135deg, var(--umk-blue) 0%, var(--umk-blue-accent) 100%); color: white;">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative overflow-hidden">
            <div class="position-relative z-1">
                <h2 class="fw-bold mb-2 text-white display-6">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h2>
                <p class="mb-0 opacity-75 fs-5">Berikut adalah ringkasan informasi KKN Mlati Norowito hari ini.</p>
            </div>
            <div class="position-absolute end-0 bottom-0 opacity-25 me-4 mb-n3 d-none d-md-block" style="transform: rotate(-15deg);">
                <i class="bi bi-rocket-takeoff-fill" style="font-size: 10rem;"></i>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-5">
        <!-- Anggota -->
        <div class="col">
            <a href="{{ route('admin.anggota.index') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 position-relative overflow-hidden">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-1 tracking-wide">Total Anggota</div>
                            <div class="display-5 fw-bolder text-dark">{{ $totalAnggota }}</div>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 text-primary d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-people-fill fs-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Proker -->
        <div class="col">
            <a href="{{ route('admin.proker.index') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 position-relative overflow-hidden">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-1 tracking-wide">Program Kerja</div>
                            <div class="display-5 fw-bolder text-dark">{{ $totalProker }}</div>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 text-success d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-kanban fs-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Kegiatan -->
        <div class="col">
            <a href="{{ route('admin.kegiatan.index') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 position-relative overflow-hidden">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-1 tracking-wide">Total Kegiatan</div>
                            <div class="display-5 fw-bolder text-dark">{{ $totalKegiatan }}</div>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 text-warning d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-calendar-check-fill fs-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Galeri -->
        <div class="col">
            <a href="{{ route('admin.galeri.index') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 position-relative overflow-hidden">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-1 tracking-wide">Galeri Foto</div>
                            <div class="display-5 fw-bolder text-dark">{{ $totalGaleri }}</div>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3 text-danger d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="bi bi-images fs-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 g-4 mb-5">
        <div class="col">
            <a href="{{ route('admin.logbook.index') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Logbook Menunggu Review</div>
                        <div class="display-6 fw-bolder text-dark">{{ $logbookMenunggu }}</div>
                    </div>
                    <i class="bi bi-journal-text fs-1 text-primary opacity-50"></i>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.absensi.riwayat') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Absensi Hari Ini</div>
                        <div class="display-6 fw-bolder text-dark">{{ $absensiHariIni }}</div>
                    </div>
                    <i class="bi bi-qr-code-scan fs-1 text-success opacity-50"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Tables & Quick Actions -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="premium-card h-100 border-0">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Kegiatan Terbaru</h5>
                    <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-medium">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if ($kegiatanTerbaru->isEmpty())
                        <div class="text-center p-5 my-3">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Belum ada kegiatan</h6>
                            <p class="text-muted mb-0">Tambahkan kegiatan pertama Anda hari ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3 fw-semibold text-muted small text-uppercase">Judul Kegiatan</th>
                                        <th class="py-3 fw-semibold text-muted small text-uppercase">Tanggal</th>
                                        <th class="pe-4 py-3 text-end fw-semibold text-muted small text-uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatanTerbaru as $item)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                    <i class="bi bi-calendar3 me-1 text-muted"></i> {{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}
                                                </span>
                                            </td>
                                            <td class="pe-4 py-3 text-end">
                                                <a href="{{ route('admin.kegiatan.edit', $item) }}" class="btn btn-sm btn-light rounded-circle text-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="premium-card h-100 border-0" style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-header bg-transparent py-4 px-4 border-bottom-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i> Aksi Cepat</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.anggota.create') }}" class="btn btn-white border shadow-sm text-start p-3 d-flex align-items-center premium-card transition-all">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                <i class="bi bi-person-plus-fill fs-5"></i>
                            </div>
                            <span class="fw-semibold text-dark">Tambah Anggota</span>
                            <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                        </a>
                        <a href="{{ route('admin.proker.create') }}" class="btn btn-white border shadow-sm text-start p-3 d-flex align-items-center premium-card transition-all">
                            <div class="bg-success bg-opacity-10 text-success rounded p-2 me-3">
                                <i class="bi bi-file-earmark-plus-fill fs-5"></i>
                            </div>
                            <span class="fw-semibold text-dark">Tambah Proker</span>
                            <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                        </a>
                        <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-white border shadow-sm text-start p-3 d-flex align-items-center premium-card transition-all">
                            <div class="bg-warning bg-opacity-10 text-warning rounded p-2 me-3">
                                <i class="bi bi-calendar-plus-fill fs-5"></i>
                            </div>
                            <span class="fw-semibold text-dark">Tambah Kegiatan</span>
                            <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                        </a>
                        <a href="{{ route('admin.galeri.create') }}" class="btn btn-white border shadow-sm text-start p-3 d-flex align-items-center premium-card transition-all">
                            <div class="bg-danger bg-opacity-10 text-danger rounded p-2 me-3">
                                <i class="bi bi-cloud-arrow-up-fill fs-5"></i>
                            </div>
                            <span class="fw-semibold text-dark">Upload Foto</span>
                            <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
