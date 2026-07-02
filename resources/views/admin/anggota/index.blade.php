<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <span>Manajemen Anggota</span>
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary btn-sm">
                + Tambah Anggota
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
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Jabatan</th>
                            <th>Urutan</th>
                            <th class="pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggota as $item)
                            @php
                                $avatarColors = ['#003366', '#1a5c99', '#2d7ab8', '#e67e22', '#27ae60', '#8e44ad', '#c0392b', '#16a085'];
                                $avatarColor = $avatarColors[($item->urutan - 1) % count($avatarColors)];
                            @endphp
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td>
                                    @if ($item->foto)
                                        <img
                                            src="{{ asset('storage/' . $item->foto) }}"
                                            alt="{{ $item->nama }}"
                                            class="rounded-circle object-fit-cover"
                                            width="40"
                                            height="40"
                                        >
                                    @else
                                        <div
                                            class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold small"
                                            style="width: 40px; height: 40px; background-color: {{ $avatarColor }};"
                                        >
                                            {{ strtoupper(substr($item->nama, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-medium">{{ $item->nama }}</td>
                                <td>{{ $item->jurusan }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->urutan }}</td>
                                <td class="pe-3">
                                    <div class="d-flex flex-wrap gap-1">
                                        <a
                                            href="{{ route('detail.anggota', $item->id) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Preview
                                        </a>
                                        <a
                                            href="{{ route('admin.anggota.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('admin.anggota.destroy', $item->id) }}"
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data anggota.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
