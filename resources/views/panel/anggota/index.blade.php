<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Manajemen Anggota</h1>
            <a href="{{ route('panel.anggota.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill fw-medium d-flex align-items-center gap-2">
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

    @if ($errors->any() && ! $errors->has('username') && ! $errors->has('password') && ! $errors->has('anggota'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->has('anggota'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first('anggota') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->has('username') || $errors->has('password'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <x-filter-daftar placeholder="Cari nama, NIM, jurusan..." :reset-url="route('panel.anggota.index')">
        <div class="col-md-3 col-lg-2">
            <label class="form-label small mb-1 fw-semibold">Jabatan</label>
            <select name="jabatan" class="form-select form-select-sm">
                <option value="">Semua</option>
                @foreach (\App\Enums\Jabatan::cases() as $j)
                    <option value="{{ $j->value }}" @selected(($jabatan ?? '') === $j->value)>{{ $j->value }}</option>
                @endforeach
            </select>
        </div>
    </x-filter-daftar>

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
                                            loading="lazy"
                                            decoding="async"
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
                                        @if (Auth::user()->isAdmin())
                                            <form
                                                action="{{ route('panel.anggota.reset-password', $item) }}"
                                                method="POST"
                                                class="mt-2"
                                                onsubmit="return confirm('Reset password {{ $item->nama }}? Password lama akan langsung tidak berlaku dan anggota harus login ulang dengan password baru.')"
                                            >
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill">
                                                    <i class="bi bi-key-fill me-1"></i> Reset Password
                                                </button>
                                            </form>
                                        @endif
                                    @elseif (Auth::user()->isAdmin())
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#akun-{{ $item->id }}">Buat Akun</button>
                                    @else
                                        <span class="badge bg-light text-muted border">Belum ada akun</span>
                                        <div class="small text-muted">Dibuat oleh admin</div>
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
                                            href="{{ route('panel.anggota.edit', $item->id) }}"
                                            class="btn btn-sm btn-light rounded-circle text-primary shadow-sm transition-all"
                                            data-bs-toggle="tooltip" title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form
                                            action="{{ route('panel.anggota.destroy', $item->id) }}"
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

    {{-- Modal di luar tabel agar Bootstrap render dengan benar --}}
    @if (Auth::user()->isAdmin())
        @foreach ($anggota as $item)
            @if (! $item->user)
                <div class="modal fade" id="akun-{{ $item->id }}" tabindex="-1" aria-labelledby="akun-label-{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('panel.anggota.akun', $item) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="akun-label-{{ $item->id }}">Buat Akun — {{ $item->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="username-{{ $item->id }}" class="form-label">Username</label>
                                        <input
                                            type="text"
                                            id="username-{{ $item->id }}"
                                            name="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username', Str::slug(Str::before($item->nama, ' '), '_')) }}"
                                            required
                                            autocomplete="off"
                                        >
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role Sistem</label>
                                        @php
                                            $roleOtomatis = \App\Penunjang\AkunAnggota::peranDariJabatan($item->jabatan);
                                        @endphp
                                        <input type="text" class="form-control" value="{{ $roleOtomatis->label() }}" readonly>
                                        <div class="form-text">
                                            Ditentukan otomatis dari jabatan <strong>{{ $item->jabatan }}</strong>.
                                            Koordinator Desa → Koordinator; selain itu → Anggota.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password-{{ $item->id }}" class="form-label">Password</label>
                                        <input
                                            type="password"
                                            id="password-{{ $item->id }}"
                                            name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            required
                                            autocomplete="new-password"
                                        >
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimal 12 karakter, huruf dan angka.</div>
                                    </div>
                                    <div class="mb-0">
                                        <label for="password-confirm-{{ $item->id }}" class="form-label">Konfirmasi Password</label>
                                        <input
                                            type="password"
                                            id="password-confirm-{{ $item->id }}"
                                            name="password_confirmation"
                                            class="form-control"
                                            required
                                            autocomplete="new-password"
                                        >
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Akun</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    <!-- DEBUG password_baru: {{ session('password_baru') ? 'ADA' : 'KOSONG' }} -->
    @if (session('password_baru'))
        <div class="modal fade show" id="modal-password-baru" tabindex="-1" aria-labelledby="modal-password-baru-label" aria-modal="true" role="dialog" data-bs-backdrop="static" style="display: block;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modal-password-baru-label">
                            Password Baru — {{ session('password_baru_untuk') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup" onclick="document.getElementById('modal-password-baru').remove(); document.getElementById('modal-password-baru-backdrop')?.remove();"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-stretch gap-2 mb-3">
                            <div
                                id="password-baru-teks"
                                class="flex-grow-1 p-3 bg-light border rounded-3 font-monospace fs-5 fw-bold text-center user-select-all"
                            >{{ session('password_baru') }}</div>
                            <button type="button" class="btn btn-outline-primary rounded-3 px-3" id="btn-salin-password" onclick="salinPasswordBaru()">
                                <i class="bi bi-clipboard"></i>
                                <span class="d-none d-sm-inline ms-1">Salin</span>
                            </button>
                        </div>
                        <p id="feedback-salin-password" class="small text-success mb-2 d-none">Tersalin!</p>
                        <div class="alert alert-warning border-0 mb-0 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            Password ini hanya ditampilkan sekali dan tidak disimpan di sistem. Catat atau screenshot sekarang, lalu bagikan ke anggota secara pribadi. Anggota akan diminta mengganti password ini saat login pertama.
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal" onclick="document.getElementById('modal-password-baru').remove(); document.getElementById('modal-password-baru-backdrop')?.remove();">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" id="modal-password-baru-backdrop"></div>
    @endif

    @push('scripts')
        @if (session('password_baru'))
            <script>
                function salinPasswordBaru() {
                    const teks = document.getElementById('password-baru-teks').textContent.trim();
                    navigator.clipboard.writeText(teks).then(function () {
                        const feedback = document.getElementById('feedback-salin-password');
                        feedback.classList.remove('d-none');
                        setTimeout(function () { feedback.classList.add('d-none'); }, 2000);
                    });
                }

                document.addEventListener('DOMContentLoaded', function () {
                    const el = document.getElementById('modal-password-baru');
                    if (! el || el.classList.contains('show')) {
                        return;
                    }
                    if (window.bootstrap?.Modal) {
                        bootstrap.Modal.getOrCreateInstance(el).show();
                    }
                });
            </script>
        @endif
    @endpush
</x-app-layout>
