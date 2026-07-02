<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0 fw-bold text-dark">Pengaturan Sistem</h1>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="premium-card h-100 border-0">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-globe2 fs-5"></i>
                    </div>
                    <h2 class="h5 fw-bold mb-0">Info Website</h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nama_dpl" class="form-label fw-semibold text-muted small text-uppercase">Nama DPL</label>
                            <input
                                type="text"
                                id="nama_dpl"
                                name="nama_dpl"
                                value="{{ old('nama_dpl', $pengaturan['nama_dpl'] ?? '') }}"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none @error('nama_dpl') is-invalid @enderror"
                            >
                            @error('nama_dpl')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nama_kelompok" class="form-label fw-semibold text-muted small text-uppercase">Nama Kelompok</label>
                            <input
                                type="text"
                                id="nama_kelompok"
                                name="nama_kelompok"
                                value="{{ old('nama_kelompok', $pengaturan['nama_kelompok'] ?? '') }}"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none @error('nama_kelompok') is-invalid @enderror"
                            >
                            @error('nama_kelompok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tagline" class="form-label fw-semibold text-muted small text-uppercase">Tagline</label>
                            <input
                                type="text"
                                id="tagline"
                                name="tagline"
                                value="{{ old('tagline', $pengaturan['tagline'] ?? '') }}"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none @error('tagline') is-invalid @enderror"
                            >
                            @error('tagline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-muted small text-uppercase">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $pengaturan['email'] ?? '') }}"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none @error('email') is-invalid @enderror"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="whatsapp" class="form-label fw-semibold text-muted small text-uppercase">WhatsApp</label>
                            <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $pengaturan['whatsapp'] ?? '') }}" class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none" placeholder="6281234567890">
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label fw-semibold text-muted small text-uppercase">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2" class="form-control bg-light border-0 px-3 py-2 shadow-none">{{ old('alamat', $pengaturan['alamat'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="maps_embed_url" class="form-label fw-semibold text-muted small text-uppercase">Google Maps Embed URL</label>
                            <input type="url" id="maps_embed_url" name="maps_embed_url" value="{{ old('maps_embed_url', $pengaturan['maps_embed_url'] ?? '') }}" class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none">
                        </div>

                        <div class="mb-4">
                            <label for="instagram" class="form-label fw-semibold text-muted small text-uppercase">Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted border-end-0 pe-0"><i class="bi bi-instagram"></i></span>
                                <input
                                    type="text"
                                    id="instagram"
                                    name="instagram"
                                    value="{{ old('instagram', $pengaturan['instagram'] ?? '') }}"
                                    class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none border-start-0 ps-2 @error('instagram') is-invalid @enderror"
                                    placeholder="@kkn_mlatinorowito2026"
                                >
                            </div>
                            @error('instagram')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="periode_kkn" class="form-label fw-semibold text-muted small text-uppercase">Periode KKN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted border-end-0 pe-0"><i class="bi bi-calendar2-range"></i></span>
                                <input
                                    type="text"
                                    id="periode_kkn"
                                    name="periode_kkn"
                                    value="{{ old('periode_kkn', $pengaturan['periode_kkn'] ?? '') }}"
                                    class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none border-start-0 ps-2 @error('periode_kkn') is-invalid @enderror"
                                    placeholder="Juli - Agustus 2026"
                                >
                            </div>
                            @error('periode_kkn')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-medium w-100">Simpan Info Website</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="premium-card h-100 border-0">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <h2 class="h5 fw-bold mb-0">Keamanan Akun</h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.pengaturan.akun') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="username" class="form-label fw-semibold text-muted small text-uppercase">Username</label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', Auth::user()->username) }}"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none @error('username') is-invalid @enderror"
                                required
                            >
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-semibold text-muted small text-uppercase">Password Saat Ini</label>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none @error('current_password') is-invalid @enderror"
                                required
                                autocomplete="current-password"
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2"><i class="bi bi-info-circle me-1"></i> Wajib diisi untuk memverifikasi identitas Anda.</div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-muted small text-uppercase">Password Baru</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2">Kosongkan jika tidak ingin mengganti password.</div>
                        </div>

                        <div class="mb-5">
                            <label for="password_confirmation" class="form-label fw-semibold text-muted small text-uppercase">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control form-control-lg bg-light border-0 px-3 py-2 fs-6 shadow-none"
                                autocomplete="new-password"
                            >
                        </div>

                        <button type="submit" class="btn btn-warning px-4 py-2 rounded-pill fw-medium w-100 text-dark">Update Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="premium-card border-0">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h2 class="h5 fw-bold mb-0"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Pengaturan Absensi QR</h2>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.pengaturan.absensi') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="absensi_jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" id="absensi_jam_mulai" name="absensi_jam_mulai" value="{{ old('absensi_jam_mulai', $pengaturan['absensi_jam_mulai'] ?? '06:00') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="absensi_jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" id="absensi_jam_selesai" name="absensi_jam_selesai" value="{{ old('absensi_jam_selesai', $pengaturan['absensi_jam_selesai'] ?? '09:00') }}" class="form-control" required>
                            </div>
                        </div>
                        <p class="small text-muted mt-3 mb-3">Anggota hanya bisa absen dalam rentang jam ini setelah scan QR di posko.</p>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Jam Absensi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
