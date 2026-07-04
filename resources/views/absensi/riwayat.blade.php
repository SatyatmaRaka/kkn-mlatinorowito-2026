<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Riwayat Absensi</h1>
            <div class="d-flex gap-2 flex-wrap">
                @if (Auth::user()->canReviewLogbook())
                    <a href="{{ route('panel.absensi.rekap') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Rekap Hari Ini</a>
                    <a href="{{ route('panel.absensi.export') }}" class="btn btn-success btn-sm rounded-pill px-3"><i class="bi bi-download me-1"></i>Export CSV</a>
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
        <x-filter-daftar placeholder="Cari nama anggota..." :reset-url="route('panel.absensi.riwayat')">
            <div class="col-md-3 col-lg-2">
                <label class="form-label small mb-1 fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm">
            </div>
        </x-filter-daftar>
    @else
        <x-filter-daftar placeholder="Cari..." :reset-url="route('panel.absensi.riwayat')" :show-search="false">
            <div class="col-md-3 col-lg-2">
                <label class="form-label small mb-1 fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm">
            </div>
        </x-filter-daftar>
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
                        <th class="py-3">Status</th>
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
                            <td class="py-3">
                                @if ($item->status === \App\Models\Absensi::STATUS_HADIR)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Hadir</span>
                                @elseif ($item->status === \App\Models\Absensi::STATUS_IZIN)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Izin</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Sakit</span>
                                @endif
                            </td>
                            <td class="py-3">{{ $item->check_in_at?->format('H:i') ?? '—' }}@if ($item->check_in_at) WIB @endif</td>
                            <td class="pe-4 py-3"><span class="badge bg-light text-dark border text-uppercase">{{ $item->metode }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->canReviewLogbook() ? 5 : 4 }}" class="text-center text-muted py-5">Belum ada data absensi.</td>
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
