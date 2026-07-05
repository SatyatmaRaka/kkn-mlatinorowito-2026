<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold">Pemetaan UKM</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('panel.ukm.cetak') }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-printer"></i> Cetak</a>
                <a href="{{ route('panel.ukm.export') }}" class="btn btn-outline-success btn-sm rounded-pill"><i class="bi bi-download"></i> Export</a>
                <a href="{{ route('panel.ukm.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="bi bi-plus-circle"></i> Tambah UKM</a>
            </div>
        </div>
    </x-slot>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form method="GET" class="mb-3"><input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm d-inline-block w-auto" placeholder="Cari nama usaha..."> <button class="btn btn-sm btn-primary">Cari</button></form>
    <div class="premium-card border-0"><div class="table-responsive"><table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>No</th><th>Nama Usaha</th><th>Jenis</th><th>Omzet/bulan</th><th>Jangkauan</th><th class="text-end">Aksi</th></tr></thead>
        <tbody>
        @forelse ($ukm as $item)
            <tr>
                <td>{{ $ukm->firstItem() + $loop->index }}</td>
                <td class="fw-semibold">{{ $item->nama_usaha }}</td>
                <td>{{ $item->jenis_usaha }}</td>
                <td>{{ $item->rata_rata_omzet ?? '—' }}</td>
                <td>{{ $item->jangkauan_pemasaran ?? '—' }}</td>
                <td class="text-end">
                    <a href="{{ route('panel.ukm.edit', $item) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('panel.ukm.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
        @empty<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data UKM.</td></tr>@endforelse
        </tbody>
    </table></div>
    @if ($ukm->hasPages())<div class="p-3">{{ $ukm->links() }}</div>@endif
    </div>
</x-app-layout>
