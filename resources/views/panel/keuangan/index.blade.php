<x-app-layout>
    <x-slot name="header">
        {{ __('Manajemen Keuangan') }}
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Pemasukan</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card bg-danger text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Total Pengeluaran</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-white-50">Saldo Saat Ini</h6>
                    <h3 class="mb-0">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Riwayat Transaksi</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('panel.keuangan.export') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-download me-1"></i> Export CSV
                </a>
                <a href="{{ route('panel.keuangan.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Catatan
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Pencatat</th>
                            <th>Terakhir Diubah</th>
                            <th>Bukti</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keuangans as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td>
                                    @if ($item->jenis === 'pemasukan')
                                        <span class="badge bg-success">Pemasukan</span>
                                    @else
                                        <span class="badge bg-danger">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="fw-semibold {{ $item->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                    {{ $item->jenis === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td class="small text-muted">
                                    @if ($item->diubahOleh)
                                        {{ $item->diubahOleh->name }}
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $item->updated_at->locale('id')->translatedFormat('d M Y H:i') }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->bukti)
                                        <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('panel.keuangan.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('panel.keuangan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada catatan keuangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($keuangans->hasPages())
                <div class="card-footer bg-white border-top-0">
                    {{ $keuangans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
