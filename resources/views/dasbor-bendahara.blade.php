<x-app-layout>
    <x-slot name="header">Dasbor Bendahara</x-slot>

    <div class="premium-card mb-4 border-0" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;">
        <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold text-white mb-2">Halo, {{ $user->name }}!</h2>
            <p class="mb-0 opacity-75">Bendahara — kelola pemasukan, pengeluaran, dan laporan keuangan KKN.</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-4">
        <div class="col">
            <div class="premium-card border-0 p-4 h-100">
                <div class="text-muted small fw-bold text-uppercase mb-1">Saldo Kas</div>
                <div class="fs-3 fw-bold text-dark">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col">
            <div class="premium-card border-0 p-4 h-100">
                <div class="text-muted small fw-bold text-uppercase mb-1">Pemasukan Bulan Ini</div>
                <div class="fs-4 fw-bold text-success">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col">
            <div class="premium-card border-0 p-4 h-100">
                <div class="text-muted small fw-bold text-uppercase mb-1">Pengeluaran Bulan Ini</div>
                <div class="fs-4 fw-bold text-danger">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('panel.laporan.keuangan') }}" class="text-decoration-none">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Total Pemasukan</div>
                    <div class="fs-5 fw-bold text-dark">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2 text-success"></i>Transaksi Terbaru</h5>
                    <a href="{{ route('panel.keuangan.index') }}" class="btn btn-sm btn-light rounded-pill">Semua</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($transaksiTerbaru as $item)
                        <a href="{{ route('panel.keuangan.edit', $item) }}" class="list-group-item list-group-item-action px-4 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $item->keterangan }}</strong>
                                    <div class="small text-muted">{{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}</div>
                                </div>
                                <span class="fw-bold {{ $item->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                    {{ $item->jenis === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-muted text-center">Belum ada transaksi tercatat.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="premium-card border-0 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('panel.keuangan.create') }}" class="btn btn-success rounded-pill py-2"><i class="bi bi-plus-circle me-2"></i>Catat Transaksi</a>
                    <a href="{{ route('panel.laporan.keuangan') }}" class="btn btn-outline-primary rounded-pill py-2"><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Keuangan</a>
                    <a href="{{ route('panel.keuangan.export') }}" class="btn btn-outline-secondary rounded-pill py-2"><i class="bi bi-download me-2"></i>Export CSV</a>
                    <a href="{{ route('panel.catatan-harian.index') }}" class="btn btn-outline-secondary rounded-pill py-2"><i class="bi bi-journal-text me-2"></i>Logbook Saya</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
