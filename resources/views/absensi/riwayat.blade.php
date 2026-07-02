<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Riwayat Absensi</h1>
            <div class="d-flex gap-2 flex-wrap">
                @if (Auth::user()->canReviewLogbook())
                    <a href="{{ route('admin.absensi.rekap') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Rekap Hari Ini</a>
                    <a href="{{ route('admin.absensi.export') }}" class="btn btn-success btn-sm rounded-pill px-3"><i class="bi bi-download me-1"></i>Export CSV</a>
                @endif
                @if (Auth::user()->canCheckInAbsensi())
                    <a href="{{ route('absensi.scan') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-qr-code-scan me-1"></i> Scan Absensi
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    @if (Auth::user()->canReviewLogbook())
        <form method="GET" class="premium-card border-0 p-3 mb-4 d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label class="form-label small mb-1">Filter tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm">
            </div>
            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill">Terapkan</button>
            @if ($tanggal)
                <a href="{{ route('admin.absensi.riwayat') }}" class="btn btn-sm btn-link">Reset</a>
            @endif
        </form>
    @endif

    <div class="premium-card border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3">Tanggal</th>
                        @if (Auth::user()->canReviewLogbook())
                            <th class="py-3">Anggota</th>
                        @endif
                        <th class="py-3">Waktu Check-in</th>
                        <th class="pe-4 py-3">Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absensi as $item)
                        <tr>
                            <td class="ps-4 py-3">{{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                            @if (Auth::user()->canReviewLogbook())
                                <td class="py-3 fw-semibold">{{ $item->anggota->nama }}</td>
                            @endif
                            <td class="py-3">{{ $item->check_in_at->format('H:i') }} WIB</td>
                            <td class="pe-4 py-3"><span class="badge bg-light text-dark border text-uppercase">{{ $item->metode }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->canReviewLogbook() ? 4 : 3 }}" class="text-center text-muted py-5">Belum ada data absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($absensi->hasPages())
            <div class="p-3 border-top">{{ $absensi->links() }}</div>
        @endif
    </div>
</x-app-layout>
