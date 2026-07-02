<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Manajemen Anggota</h1>
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-medium d-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill"></i> Tambah Anggota
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
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Foto</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Nama</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Jurusan</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Jabatan</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Akun Login</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase text-center">Urutan</th>
                            <th class="pe-4 py-3 fw-semibold text-muted small text-uppercase text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($anggota as $item)
                            @php
                                $avatarColors = ['#003366', '#1a5c99', '#2d7ab8', '#e67e22', '#27ae60', '#8e44ad', '#c0392b', '#16a085'];
                                $avatarColor = $avatarColors[($item->urutan - 1) % count($avatarColors)];
                            @endphp
                            <tr>
                                <td class="ps-4 py-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    @if ($item->foto)
                                        <img
                                            src="{{ asset('storage/' . $item->foto) }}"
                                            alt="{{ $item->nama }}"
                                            class="rounded-circle object-fit-cover border shadow-sm"
                                            width="45"
                                            height="45"
                                        >
                                    @else
                                        <div
                                            class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                            style="width: 45px; height: 45px; background-color: {{ $avatarColor }}; font-size: 0.9rem;"
                                        >
                                            {{ strtoupper(substr($item->nama, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-bold text-dark">{{ $item->nama }}</td>
                                <td class="py-3">{{ $item->jurusan }}</td>
                                <td class="py-3"><span class="badge bg-light text-dark border">{{ $item->jabatan }}</span></td>
                                <td class="py-3">
                                    @if ($item->user)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $item->user->username }}</span>
                                        <div class="small text-muted">{{ $item->user->role->label() }}</div>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#akun-{{ $item->id }}">Buat Akun</button>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-secondary rounded-pill px-3">{{ $item->urutan }}</span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a
                                            href="{{ route('detail.anggota', $item->id) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-light rounded-circle text-secondary shadow-sm transition-all"
                                            data-bs-toggle="tooltip" title="Preview"
                                        >
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a
                                            href="{{ route('admin.anggota.edit', $item->id) }}"
                                            class="btn btn-sm btn-light rounded-circle text-primary shadow-sm transition-all"
                                            data-bs-toggle="tooltip" title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form
                                            action="{{ route('admin.anggota.destroy', $item->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus anggota ini?')"
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

                            @if (! $item->user)
                                <div class="modal fade" id="akun-{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.anggota.akun', $item) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Buat Akun — {{ $item->nama }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Username</label>
                                                        <input type="text" name="username" class="form-control" value="{{ Str::slug(Str::before($item->nama, ' '), '_') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Role Sistem</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="anggota" selected>Anggota</option>
                                                            <option value="koordinator" @selected(in_array($item->jabatan, ['Koordinator Desa', 'Wakil Koordinator']))>Koordinator</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Password</label>
                                                        <input type="password" name="password" class="form-control" required>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label">Konfirmasi Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan Akun</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="bi bi-people text-muted fs-1"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">Belum ada data anggota</h6>
                                        <p class="text-muted small mb-0">Klik tombol Tambah Anggota untuk mulai menambahkan.</p>
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
