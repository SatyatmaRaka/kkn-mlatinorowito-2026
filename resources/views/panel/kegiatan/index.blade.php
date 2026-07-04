<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Manajemen Kegiatan</h1>
            <a href="{{ route('panel.kegiatan.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-calendar-plus-fill"></i> Tambah Kegiatan
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <x-filter-daftar placeholder="Cari judul kegiatan..." :reset-url="route('panel.kegiatan.index')" />

    <div class="premium-card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle border-top-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 fw-semibold text-muted small text-uppercase">No</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase text-center">Foto</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Judul</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Tanggal</th>
                            <th class="pe-4 py-3 fw-semibold text-muted small text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($kegiatan as $item)
                            <tr>
                                <td class="ps-4 py-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="py-3 text-center">
                                    @if ($item->foto)
                                        <img
                                            src="{{ asset('storage/' . $item->foto) }}"
                                            alt="{{ $item->judul }}"
                                            class="rounded object-fit-cover shadow-sm border"
                                            width="55"
                                            height="55"
                                        >
                                    @else
                                        <div
                                            class="rounded bg-light d-inline-flex align-items-center justify-content-center text-muted shadow-sm border"
                                            style="width: 55px; height: 55px;"
                                        >
                                            <i class="bi bi-image fs-4 text-black-50"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-bold text-dark">{{ $item->judul }}</td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <i class="bi bi-calendar3 me-1 text-muted"></i> {{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a
                                            href="{{ route('detail.kegiatan', $item->id) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-light rounded-circle text-secondary shadow-sm transition-all"
                                            data-bs-toggle="tooltip" title="Preview"
                                        >
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a
                                            href="{{ route('panel.kegiatan.edit', $item->id) }}"
                                            class="btn btn-sm btn-light rounded-circle text-primary shadow-sm transition-all"
                                            data-bs-toggle="tooltip" title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form
                                            action="{{ route('panel.kegiatan.destroy', $item->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')"
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
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="bi bi-calendar-event text-muted fs-1"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">Belum ada data kegiatan</h6>
                                        <p class="text-muted small mb-0">Klik tombol Tambah Kegiatan untuk mulai menambahkan.</p>
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
