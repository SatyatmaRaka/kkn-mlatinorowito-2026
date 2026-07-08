<x-app-layout>
    <x-slot name="header">Dashboard {{ $divisi['judul'] }}</x-slot>

    <div class="premium-card mb-4 border-0" style="background: linear-gradient(135deg, var(--umk-blue) 0%, var(--umk-blue-accent) 100%); color: white;">
        <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold text-white mb-2">Halo, {{ $user->anggota?->nama ?? $user->name }}!</h2>
            <p class="mb-0 opacity-75">{{ $divisi['judul'] }} — {{ $divisi['deskripsi'] }}</p>
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
        <div class="col-md-4">
            <div class="premium-card border-0 p-4 h-100">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-{{ $divisi['warna'] }} bg-opacity-10 text-{{ $divisi['warna'] }} rounded p-2">
                        <i class="bi {{ $divisi['ikon'] }} fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Divisi Anda</div>
                        <div class="fw-bold">{{ $divisi['judul'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="premium-card border-0 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="bi {{ $divisi['ikon'] }} me-2 text-{{ $divisi['warna'] }}"></i>Tugas Divisi {{ $divisi['judul'] }}</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <p class="text-muted mb-3">{{ $divisi['deskripsi'] }}</p>
                    <ul class="mb-0 ps-3">
                        @foreach ($divisi['tugas'] as $tugas)
                            <li class="mb-2">{{ $tugas }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

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
                        <div class="p-4 text-muted text-center">Belum ada logbook. Mulai tulis aktivitas harian divisi Anda.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="premium-card border-0 p-4 d-grid gap-2">
                <h5 class="fw-bold mb-1"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat Divisi</h5>
                @foreach ($divisi['aksi_cepat'] as $aksi)
                    <a href="{{ route($aksi['route']) }}" class="btn btn-{{ $aksi['varian'] }} rounded-pill py-3">
                        <i class="bi {{ $aksi['ikon'] }} me-2"></i>{{ $aksi['label'] }}
                    </a>
                @endforeach
                <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary rounded-pill py-3"><i class="bi bi-globe me-2"></i>Lihat Website Publik</a>
            </div>
        </div>
    </div>
</x-app-layout>
