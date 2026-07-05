<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Buku Tamu</h1>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('panel.buku-tamu.cetak', request()->only(['tanggal'])) }}" class="btn btn-outline-secondary btn-sm rounded-pill" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
                <a href="{{ route('panel.buku-tamu.export') }}" class="btn btn-outline-success btn-sm rounded-pill"><i class="bi bi-download"></i> Export CSV</a>
                <a href="{{ route('panel.buku-tamu.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="bi bi-plus-circle"></i> Catat Tamu</a>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-semibold">Cari nama tamu</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Nama tamu...">
        </div>
        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('panel.buku-tamu.index') }}" class="btn btn-sm btn-light">Reset</a>
        </div>
    </form>

    <div class="premium-card border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tanggal</th>
                        <th>Nama Tamu</th>
                        <th>Alamat/Jabatan</th>
                        <th>Keperluan</th>
                        <th>Mhs Menemui</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tamu as $item)
                        <tr>
                            <td class="ps-4">{{ $tamu->firstItem() + $loop->index }}</td>
                            <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold">{{ $item->nama_tamu }}</td>
                            <td>{{ $item->alamat_jabatan ?? '—' }}</td>
                            <td>{{ Str::limit($item->keperluan, 60) }}</td>
                            <td>{{ $item->anggota?->nama ?? '—' }}</td>
                            <td class="pe-4 text-end">
                                @can('update', $item)
                                    <a href="{{ route('panel.buku-tamu.edit', $item) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('panel.buku-tamu.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data tamu ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada tamu dicatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tamu->hasPages())
            <div class="p-3">{{ $tamu->links() }}</div>
        @endif
    </div>
</x-app-layout>
