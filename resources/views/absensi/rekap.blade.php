<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold">Rekap Absensi — {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}</h1>
            <div class="d-flex gap-2 flex-wrap">
                <form method="GET" class="d-flex gap-2">
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill">Filter</button>
                </form>
                <a href="{{ route('panel.absensi.export', ['tanggal_mulai' => $tanggal, 'tanggal_selesai' => $tanggal]) }}" class="btn btn-sm btn-success rounded-pill">
                    <i class="bi bi-download me-1"></i> Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="premium-card border-0 p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold">Total Anggota</div>
                <div class="display-6 fw-bold">{{ $anggotaDenganAkun->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-card border-0 p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold text-success">Hadir</div>
                <div class="display-6 fw-bold text-success">{{ $hadir->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-card border-0 p-4 text-center">
                <div class="text-muted small text-uppercase fw-bold text-danger">Belum Hadir</div>
                <div class="display-6 fw-bold text-danger">{{ $belum->count() }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-success">Sudah Absen</div>
                <ul class="list-group list-group-flush">
                    @forelse ($absensiHari as $item)
                        <li class="list-group-item d-flex justify-content-between px-4">
                            <span>{{ $item->anggota->nama }}</span>
                            <span class="text-muted">{{ $item->check_in_at->format('H:i') }} WIB</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted px-4 py-4">Belum ada yang absen.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-3 px-4 border-bottom fw-bold text-danger">Belum Absen</div>
                <ul class="list-group list-group-flush">
                    @forelse ($belum as $item)
                        <li class="list-group-item px-4">{{ $item->nama }} <span class="text-muted small">({{ $item->jabatan }})</span></li>
                    @empty
                        <li class="list-group-item text-success px-4 py-4">Semua anggota sudah absen!</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
