<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <span>Manajemen Kegiatan</span>
            <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary btn-sm">
                + Tambah Kegiatan
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
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th class="pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan as $item)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td>
                                    @if ($item->foto)
                                        <img
                                            src="{{ asset('storage/' . $item->foto) }}"
                                            alt="{{ $item->judul }}"
                                            class="rounded object-fit-cover"
                                            width="48"
                                            height="48"
                                        >
                                    @else
                                        <div
                                            class="rounded bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-muted small"
                                            style="width: 48px; height: 48px;"
                                        >
                                            —
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-medium">{{ $item->judul }}</td>
                                <td>{{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                                <td class="pe-3">
                                    <div class="d-flex flex-wrap gap-1">
                                        <a
                                            href="{{ route('detail.kegiatan', $item->id) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Preview
                                        </a>
                                        <a
                                            href="{{ route('admin.kegiatan.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('admin.kegiatan.destroy', $item->id) }}"
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
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
