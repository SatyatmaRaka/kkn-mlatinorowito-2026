<x-app-layout>
    <x-slot name="header">Dasbor Sekretaris</x-slot>

    <div class="premium-card mb-4 border-0" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white;">
        <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold text-white mb-2">Halo, {{ $user->name }}!</h2>
            <p class="mb-0 opacity-75">Sekretaris — kelola arsip surat masuk dan surat keluar KKN.</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 g-4 mb-4">
        <div class="col">
            <a href="{{ route('panel.surat.index', ['jenis' => 'masuk']) }}" class="text-decoration-none">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Surat Masuk</div>
                    <div class="display-6 fw-bold">{{ $suratMasuk }}</div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('panel.surat.index', ['jenis' => 'keluar']) }}" class="text-decoration-none">
                <div class="premium-card border-0 p-4 h-100">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Surat Keluar</div>
                    <div class="display-6 fw-bold">{{ $suratKeluar }}</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-envelope-paper me-2 text-info"></i>Surat Terbaru</h5>
                    <a href="{{ route('panel.surat.index') }}" class="btn btn-sm btn-light rounded-pill">Semua</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($suratTerbaru as $surat)
                        <a href="{{ route('panel.surat.edit', $surat) }}" class="list-group-item list-group-item-action px-4 py-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <strong>{{ $surat->perihal }}</strong>
                                    <div class="small text-muted">{{ $surat->asal_tujuan }}</div>
                                </div>
                                <span class="badge {{ $surat->isMasuk() ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-dark' }}">{{ $surat->labelJenis() }}</span>
                            </div>
                            <small class="text-muted">{{ $surat->tanggal->locale('id')->translatedFormat('d F Y') }}</small>
                        </a>
                    @empty
                        <div class="p-4 text-muted text-center">Belum ada surat tercatat.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="premium-card border-0 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('panel.surat.create', ['jenis' => 'keluar', 'kategori' => 'kelurahan']) }}" class="btn btn-info text-white rounded-pill py-2"><i class="bi bi-building me-2"></i>Surat ke Kelurahan</a>
                    <a href="{{ route('panel.surat.create', ['jenis' => 'keluar', 'kategori' => 'rt']) }}" class="btn btn-outline-info rounded-pill py-2"><i class="bi bi-houses me-2"></i>Surat ke RT</a>
                    <a href="{{ route('panel.surat.create', ['jenis' => 'keluar', 'kategori' => 'rw']) }}" class="btn btn-outline-info rounded-pill py-2"><i class="bi bi-diagram-3 me-2"></i>Surat ke RW</a>
                    <a href="{{ route('panel.surat.create', ['jenis' => 'masuk']) }}" class="btn btn-outline-secondary rounded-pill py-2"><i class="bi bi-inbox me-2"></i>Catat Surat Masuk</a>
                    <a href="{{ route('panel.catatan-harian.index') }}" class="btn btn-outline-primary rounded-pill py-2"><i class="bi bi-journal-text me-2"></i>Logbook Saya</a>
                    <a href="{{ route('panel.absensi.riwayat') }}" class="btn btn-outline-secondary rounded-pill py-2"><i class="bi bi-clock-history me-2"></i>Riwayat Absensi</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
