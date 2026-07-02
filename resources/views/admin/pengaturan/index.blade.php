<x-app-layout>
    <x-slot name="header">
        Pengaturan
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 fw-semibold mb-4">Info Website</h2>

                    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_dpl" class="form-label">Nama DPL</label>
                            <input
                                type="text"
                                id="nama_dpl"
                                name="nama_dpl"
                                value="{{ old('nama_dpl', $pengaturan['nama_dpl'] ?? '') }}"
                                class="form-control @error('nama_dpl') is-invalid @enderror"
                            >
                            @error('nama_dpl')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_kelompok" class="form-label">Nama Kelompok</label>
                            <input
                                type="text"
                                id="nama_kelompok"
                                name="nama_kelompok"
                                value="{{ old('nama_kelompok', $pengaturan['nama_kelompok'] ?? '') }}"
                                class="form-control @error('nama_kelompok') is-invalid @enderror"
                            >
                            @error('nama_kelompok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tagline" class="form-label">Tagline</label>
                            <input
                                type="text"
                                id="tagline"
                                name="tagline"
                                value="{{ old('tagline', $pengaturan['tagline'] ?? '') }}"
                                class="form-control @error('tagline') is-invalid @enderror"
                            >
                            @error('tagline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $pengaturan['email'] ?? '') }}"
                                class="form-control @error('email') is-invalid @enderror"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="instagram" class="form-label">Instagram</label>
                            <input
                                type="text"
                                id="instagram"
                                name="instagram"
                                value="{{ old('instagram', $pengaturan['instagram'] ?? '') }}"
                                class="form-control @error('instagram') is-invalid @enderror"
                                placeholder="@kkn_mlatinorowito2026"
                            >
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="periode_kkn" class="form-label">Periode KKN</label>
                            <input
                                type="text"
                                id="periode_kkn"
                                name="periode_kkn"
                                value="{{ old('periode_kkn', $pengaturan['periode_kkn'] ?? '') }}"
                                class="form-control @error('periode_kkn') is-invalid @enderror"
                                placeholder="Juli - Agustus 2026"
                            >
                            @error('periode_kkn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Info Website</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 fw-semibold mb-4">Ganti Username & Password</h2>

                    <form action="{{ route('admin.pengaturan.akun') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', Auth::user()->username) }}"
                                class="form-control @error('username') is-invalid @enderror"
                                required
                            >
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kosongkan jika tidak ingin mengganti password.</div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                autocomplete="new-password"
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">Update Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
