<x-app-layout>
    <x-slot name="header">
        Tambah Anggota
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('panel.anggota.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nim" class="form-label">NIM (Opsional)</label>
                    <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}">
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
                        value="{{ old('jurusan') }}"
                        class="form-control @error('jurusan') is-invalid @enderror"
                        required
                    >
                    @error('jurusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <select id="jabatan" name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" required>
                        @foreach (\App\Enums\Jabatan::cases() as $jabatanOption)
                            <option value="{{ $jabatanOption->value }}" @selected(old('jabatan', \App\Enums\Jabatan::Humas->value) === $jabatanOption->value)>
                                {{ $jabatanOption->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
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
                    >{{ old('deskripsi') }}</textarea>
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
                        value="{{ old('urutan', 0) }}"
                        class="form-control @error('urutan') is-invalid @enderror"
                        required
                    >
                    @error('urutan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('panel.anggota.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
