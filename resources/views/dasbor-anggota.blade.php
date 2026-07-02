<x-app-layout>
    <x-slot name="header">Dashboard Anggota</x-slot>

    <div class="premium-card mb-4 border-0" style="background: linear-gradient(135deg, var(--umk-blue) 0%, var(--umk-blue-accent) 100%); color: white;">
        <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold text-white mb-2">Halo, {{ Auth::user()->anggota?->nama ?? Auth::user()->name }}!</h2>
            <p class="mb-0 opacity-75">{{ Auth::user()->anggota?->jabatan ?? 'Anggota KKN' }} — {{ Auth::user()->role->label() }}</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="premium-card border-0 p-4">
                <div class="text-muted small text-uppercase fw-bold mb-1">Absensi Bulan Ini</div>
                <div class="display-6 fw-bold">{{ $absensiBulanIni }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-card border-0 p-4">
                <div class="text-muted small text-uppercase fw-bold mb-1">Status Hari Ini</div>
                <div class="fs-4 fw-bold {{ $sudahAbsenHariIni ? 'text-success' : 'text-warning' }}">
                    {{ $sudahAbsenHariIni ? 'Sudah Absen' : 'Belum Absen' }}
                </div>
            </div>
        </div>
        @if (Auth::user()->canReviewLogbook())
            <div class="col-md-4">
                <div class="premium-card border-0 p-4">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Logbook Menunggu</div>
                    <div class="display-6 fw-bold">{{ $logbookMenunggu }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between">
                    <h5 class="mb-0 fw-bold">Logbook Terbaru</h5>
                    <a href="{{ route('panel.catatan-harian.index') }}" class="btn btn-sm btn-light rounded-pill">Semua</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($logbooks as $logbook)
                        <a href="{{ route('panel.catatan-harian.edit', $logbook) }}" class="list-group-item list-group-item-action px-4 py-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $logbook->judul }}</strong>
                                <span class="badge bg-light text-dark">{{ $logbook->status }}</span>
                            </div>
                            <small class="text-muted">{{ $logbook->tanggal->locale('id')->translatedFormat('d F Y') }}</small>
                        </a>
                    @empty
                        <div class="p-4 text-muted text-center">Belum ada logbook. Mulai tulis aktivitas harian Anda.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="premium-card border-0 p-4 d-grid gap-2">
                <a href="{{ route('absensi.scan') }}" class="btn btn-primary rounded-pill py-3"><i class="bi bi-qr-code-scan me-2"></i>Absensi Posko</a>
                <a href="{{ route('panel.catatan-harian.create') }}" class="btn btn-outline-primary rounded-pill py-3"><i class="bi bi-journal-plus me-2"></i>Tulis Logbook</a>
                <a href="{{ route('panel.absensi.riwayat') }}" class="btn btn-outline-secondary rounded-pill py-3"><i class="bi bi-clock-history me-2"></i>Riwayat Absensi</a>
            </div>
        </div>
    </div>
</x-app-layout>
