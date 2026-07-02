<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <span>Manajemen Program Kerja</span>
            <a href="{{ route('admin.proker.create') }}" class="btn btn-primary btn-sm">
                + Tambah Program Kerja
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Icon</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Urutan</th>
                            <th class="pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programKerja as $item)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td class="fs-4">{{ $item->icon ?? '📋' }}</td>
                                <td class="fw-medium">{{ $item->judul }}</td>
                                <td>
                                    @if ($item->status === 'Aktif')
                                        <span class="badge bg-success">{{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->urutan }}</td>
                                <td class="pe-3">
                                    <div class="d-flex flex-wrap gap-1">
                                        <a
                                            href="{{ route('detail.proker', $item->id) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Preview
                                        </a>
                                        <a
                                            href="{{ route('admin.proker.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('admin.proker.destroy', $item->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada data program kerja.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
