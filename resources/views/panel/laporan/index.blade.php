<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold">Laporan KKN</h1>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('panel.laporan.cetak', request()->query()) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="bi bi-printer me-1"></i> Cetak
                </a>
                <a href="{{ route('panel.laporan.export', request()->query()) }}" class="btn btn-success btn-sm rounded-pill">
                    <i class="bi bi-download me-1"></i> Export Ringkasan CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="premium-card border-0 mb-4 p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $mulai }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ $selesai }}" class="form-control">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Tampilkan Laporan</button>
            </div>
        </form>
        <p class="text-muted small mb-0 mt-3">Periode: <strong>{{ $ringkasan['periode']['label'] }}</strong></p>
    </div>

    <div class="row g-4 mb-4">
        @if (Auth::user()->canReviewLogbook())
            <div class="col-md-6 col-xl-3">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small text-uppercase fw-bold mb-2">Absensi</div>
                    <div class="display-6 fw-bold">{{ $ringkasan['absensi']['total'] }}</div>
                    <div class="small text-muted">Total check-in periode</div>
                    <div class="small mt-2">Hari ini: <strong>{{ $ringkasan['absensi']['hari_ini'] }}</strong></div>
                    <a href="{{ route('panel.absensi.export', ['tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai]) }}" class="btn btn-sm btn-outline-success mt-3">Export CSV Absensi</a>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small text-uppercase fw-bold mb-2">Logbook</div>
                    <ul class="list-unstyled mb-0 small">
                        <li>Menunggu review: <strong class="text-warning">{{ $ringkasan['logbook']['submitted'] }}</strong></li>
                        <li>Disetujui: <strong class="text-success">{{ $ringkasan['logbook']['approved'] }}</strong></li>
                        <li>Ditolak: <strong class="text-danger">{{ $ringkasan['logbook']['rejected'] }}</strong></li>
                        <li>Draft: <strong>{{ $ringkasan['logbook']['draft'] }}</strong></li>
                    </ul>
                    <a href="{{ route('panel.catatan-harian.export', ['tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai]) }}" class="btn btn-sm btn-outline-success mt-3">Export CSV Logbook</a>
                </div>
            </div>
        @endif

        @if (Auth::user()->canManageKeuangan())
            <div class="col-md-6 col-xl-3">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small text-uppercase fw-bold mb-2">Keuangan</div>
                    <div class="small mb-1">Pemasukan: <strong class="text-success">Rp {{ number_format($ringkasan['keuangan']['pemasukan'], 0, ',', '.') }}</strong></div>
                    <div class="small mb-1">Pengeluaran: <strong class="text-danger">Rp {{ number_format($ringkasan['keuangan']['pengeluaran'], 0, ',', '.') }}</strong></div>
                    <div class="fs-4 fw-bold mt-2">Rp {{ number_format($ringkasan['keuangan']['saldo'], 0, ',', '.') }}</div>
                    <div class="small text-muted">Saldo periode</div>
                    <a href="{{ route('panel.keuangan.export', ['tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai]) }}" class="btn btn-sm btn-outline-success mt-3">Export CSV Keuangan</a>
                </div>
            </div>
        @endif
    </div>

    <div class="alert alert-info border-0 small mb-0">
        <i class="bi bi-arrow-repeat me-1"></i>
        Dasbor & rekap absensi diperbarui otomatis setiap 30 detik (real-time via polling).
    </div>
</x-app-layout>
