<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Manajemen Program Kerja</h1>
            <a href="{{ route('admin.proker.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle-fill"></i> Tambah Program Kerja
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="premium-card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle border-top-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 fw-semibold text-muted small text-uppercase">No</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase text-center">Icon</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Judul</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Status</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase text-center">Urutan</th>
                            <th class="pe-4 py-3 fw-semibold text-muted small text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($programKerja as $item)
                            <tr>
                                <td class="ps-4 py-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="py-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm" style="width: 40px; height: 40px;">
                                        <span class="fs-5">{{ $item->icon ?? '📋' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 fw-bold text-dark">{{ $item->judul }}</td>
                                <td class="py-3">
                                    @if ($item->status === 'Aktif')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> {{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-clock-fill me-1"></i> {{ $item->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-secondary rounded-pill px-3">{{ $item->urutan }}</span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a
                                            href="{{ route('detail.proker', $item->id) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-light rounded-circle text-secondary shadow-sm transition-all"
                                            data-bs-toggle="tooltip" title="Preview"
                                        >
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a
                                            href="{{ route('admin.proker.edit', $item->id) }}"
                                            class="btn btn-sm btn-light rounded-circle text-primary shadow-sm transition-all"
                                            data-bs-toggle="tooltip" title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form
                                            action="{{ route('admin.proker.destroy', $item->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus program kerja ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light rounded-circle text-danger shadow-sm transition-all" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="bi bi-kanban text-muted fs-1"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">Belum ada data program kerja</h6>
                                        <p class="text-muted small mb-0">Klik tombol Tambah Program Kerja untuk mulai menambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
