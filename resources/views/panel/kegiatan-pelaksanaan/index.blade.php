<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold">Kegiatan Pelaksanaan</h1>
            <a href="{{ route('panel.kegiatan-pelaksanaan.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="bi bi-plus-circle"></i> Tambah Kegiatan</a>
        </div>
    </x-slot>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto"><input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm"></div>
        <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button></div>
    </form>
    <div class="premium-card border-0"><div class="table-responsive"><table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>No</th><th>Tanggal</th><th>Nama Kegiatan</th><th>Tempat</th><th>Peserta</th><th>Tugas Tim</th><th class="text-end">Aksi</th></tr></thead>
        <tbody>
        @forelse ($kegiatan as $item)
            <tr>
                <td>{{ $kegiatan->firstItem() + $loop->index }}</td>
                <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                <td><a href="{{ route('panel.kegiatan-pelaksanaan.show', $item) }}" class="fw-semibold text-decoration-none">{{ $item->nama_kegiatan }}</a></td>
                <td>{{ $item->tempat }}</td>
                <td>{{ $item->peserta_masyarakat_count }}</td>
                <td>{{ $item->tugas_tim_count }}</td>
                <td class="text-end">
                    <a href="{{ route('panel.kegiatan-pelaksanaan.show', $item) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                    @can('update', $item)
                        <a href="{{ route('panel.kegiatan-pelaksanaan.edit', $item) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('panel.kegiatan-pelaksanaan.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan?')">@csrf @method('DELETE')<button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada kegiatan.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if ($kegiatan->hasPages())<div class="p-3">{{ $kegiatan->links() }}</div>@endif
    </div>
</x-app-layout>
