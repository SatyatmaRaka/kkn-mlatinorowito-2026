<x-app-layout>
    <x-slot name="header">
        Ringkasan Dasbor
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
    <div class="row row-cols-1 row-cols-sm-2 g-4 mb-5">
        <div class="col">
            <a href="{{ route('panel.anggota.index') }}" class="text-decoration-none">
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
        <div class="col">
            <a href="{{ route('panel.program-kerja.index') }}" class="text-decoration-none">
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
    </div>

    <div class="row row-cols-1 row-cols-sm-2 g-4 mb-5">
        <div class="col">
            <a href="{{ route('panel.catatan-harian.index') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Logbook Menunggu Review</div>
                        <div class="display-6 fw-bolder text-dark" id="live-logbook-menunggu">{{ $logbookMenunggu }}</div>
                    </div>
                    <i class="bi bi-journal-text fs-1 text-primary opacity-50"></i>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('panel.absensi.riwayat') }}" class="text-decoration-none">
                <div class="premium-card h-100 border-0 p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase mb-1">Absensi Hari Ini</div>
                        <div class="display-6 fw-bolder text-dark" id="live-absensi-hari-ini">{{ $absensiHariIni }}</div>
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
                    <h5 class="mb-0 fw-bold"><i class="bi bi-kanban me-2 text-primary"></i> Program Kerja</h5>
                    <a href="{{ route('panel.program-kerja.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-medium">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if ($prokerTerbaru->isEmpty())
                        <div class="text-center p-5 my-3">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-inbox text-muted fs-1"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Belum ada program kerja</h6>
                            <p class="text-muted mb-0">Tambahkan program kerja pertama Anda.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3 fw-semibold text-muted small text-uppercase">Judul</th>
                                        <th class="py-3 fw-semibold text-muted small text-uppercase">Status</th>
                                        <th class="pe-4 py-3 text-end fw-semibold text-muted small text-uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prokerTerbaru as $item)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                            </td>
                                            <td class="py-3">
                                                @if ($item->status === 'Aktif')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">{{ $item->status }}</span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            <td class="pe-4 py-3 text-end">
                                                <a href="{{ route('panel.program-kerja.edit', $item) }}" class="btn btn-sm btn-light rounded-circle text-primary" data-bs-toggle="tooltip" title="Edit">
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
                        <a href="{{ route('panel.anggota.create') }}" class="btn btn-white border shadow-sm text-start p-3 d-flex align-items-center premium-card transition-all">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                                <i class="bi bi-person-plus-fill fs-5"></i>
                            </div>
                            <span class="fw-semibold text-dark">Tambah Anggota</span>
                            <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                        </a>
                        <a href="{{ route('panel.program-kerja.create') }}" class="btn btn-white border shadow-sm text-start p-3 d-flex align-items-center premium-card transition-all">
                            <div class="bg-success bg-opacity-10 text-success rounded p-2 me-3">
                                <i class="bi bi-file-earmark-plus-fill fs-5"></i>
                            </div>
                            <span class="fw-semibold text-dark">Tambah Proker</span>
                            <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                        </a>
                        <a href="{{ route('panel.pengaturan.index') }}" class="btn btn-white border shadow-sm text-start p-3 d-flex align-items-center premium-card transition-all">
                            <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2 me-3">
                                <i class="bi bi-gear-fill fs-5"></i>
                            </div>
                            <span class="fw-semibold text-dark">Pengaturan Website</span>
                            <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const url = @json(route('panel.api.live.dasbor'));
            async function refreshDasbor() {
                try {
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    const logbook = document.getElementById('live-logbook-menunggu');
                    const absensi = document.getElementById('live-absensi-hari-ini');
                    if (logbook) logbook.textContent = data.logbook_menunggu;
                    if (absensi) absensi.textContent = data.absensi_hari_ini;
                } catch (e) {}
            }
            refreshDasbor();
            setInterval(refreshDasbor, 30000);
        })();
    </script>
    @endpush
</x-app-layout>
