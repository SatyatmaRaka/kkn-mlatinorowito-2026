<x-app-layout>
    <x-slot name="header">Dasbor {{ $judulPeran ?? 'Koordinator' }}</x-slot>

    <div class="premium-card mb-4 border-0" style="background: linear-gradient(135deg, var(--umk-blue) 0%, var(--umk-blue-accent) 100%); color: white;">
        <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold text-white mb-2">Halo, {{ $user->name }}!</h2>
            <p class="mb-0 opacity-75">{{ $judulPeran ?? 'Koordinator Desa' }} — {{ $deskripsiPeran ?? 'Pantau logbook, absensi, dan laporan operasional KKN.' }}</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-3 g-4 mb-4">
        <div class="col">
            <a href="{{ route('panel.catatan-harian.index') }}" class="text-decoration-none">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Logbook Menunggu Review</div>
                    <div class="display-6 fw-bold text-primary" id="live-logbook-menunggu">{{ $logbookMenunggu }}</div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('panel.absensi.rekap') }}" class="text-decoration-none">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Absensi Hari Ini</div>
                    <div class="display-6 fw-bold text-success" id="live-absensi-hari-ini">{{ $absensiHariIni }}</div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('panel.absensi.rekap') }}" class="text-decoration-none">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Belum Absen Hari Ini</div>
                    <div class="display-6 fw-bold text-warning">{{ $belumAbsen }}</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2 text-primary"></i>Logbook Perlu Review</h5>
                    <a href="{{ route('panel.catatan-harian.index') }}" class="btn btn-sm btn-light rounded-pill">Semua</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($logbookReview as $logbook)
                        <a href="{{ route('panel.catatan-harian.edit', $logbook) }}" class="list-group-item list-group-item-action px-4 py-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $logbook->judul }}</strong>
                                <span class="badge bg-warning-subtle text-warning-emphasis">Menunggu</span>
                            </div>
                            <small class="text-muted">{{ $logbook->user->anggota?->nama ?? $logbook->user->name }} · {{ $logbook->tanggal->locale('id')->translatedFormat('d F Y') }}</small>
                        </a>
                    @empty
                        <div class="p-4 text-muted text-center">Tidak ada logbook yang menunggu review.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="premium-card border-0 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('panel.catatan-harian.index') }}" class="btn btn-primary rounded-pill py-2"><i class="bi bi-journal-check me-2"></i>Review Logbook</a>
                    <a href="{{ route('panel.absensi.rekap') }}" class="btn btn-outline-success rounded-pill py-2"><i class="bi bi-clipboard-check me-2"></i>Rekap Absensi</a>
                    <a href="{{ route('panel.absensi.qr') }}" class="btn btn-outline-primary rounded-pill py-2"><i class="bi bi-qr-code me-2"></i>Cetak QR Absensi</a>
                    <a href="{{ route('panel.laporan.index') }}" class="btn btn-outline-secondary rounded-pill py-2"><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan KKN</a>
                    @if ($user->canManageKeuangan())
                        <a href="{{ route('panel.keuangan.index') }}" class="btn btn-outline-secondary rounded-pill py-2"><i class="bi bi-wallet2 me-2"></i>Keuangan</a>
                    @endif
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
