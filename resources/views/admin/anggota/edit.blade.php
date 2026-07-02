<x-app-layout>
    <x-slot name="header">
        Edit Anggota
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $anggota->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nim" class="form-label">NIM (Opsional)</label>
                    <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim', $anggota->nim) }}">
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input
                        type="text"
                        id="jurusan"
                        name="jurusan"
                        value="{{ old('jurusan', $anggota->jurusan) }}"
                        class="form-control @error('jurusan') is-invalid @enderror"
                        required
                    >
                    @error('jurusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input
                        type="text"
                        id="jabatan"
                        name="jabatan"
                        value="{{ old('jabatan', $anggota->jabatan) }}"
                        class="form-control @error('jabatan') is-invalid @enderror"
                        required
                    >
                    @error('jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    @if ($anggota->foto)
                        <div class="mb-2">
                            <p class="form-label mb-1">Foto saat ini:</p>
                            <img
                                src="{{ asset('storage/' . $anggota->foto) }}"
                                alt="{{ $anggota->nama }}"
                                class="rounded border"
                                style="max-width: 120px; max-height: 120px; object-fit: cover;"
                            >
                        </div>
                    @endif

                    <label for="foto" class="form-label">Foto Baru</label>
                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept="image/*"
                        class="form-control @error('foto') is-invalid @enderror"
                    >
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                    >{{ old('deskripsi', $anggota->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="urutan" class="form-label">Urutan</label>
                    <input
                        type="number"
                        id="urutan"
                        name="urutan"
                        value="{{ old('urutan', $anggota->urutan) }}"
                        class="form-control @error('urutan') is-invalid @enderror"
                        required
                    >
                    @error('urutan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
