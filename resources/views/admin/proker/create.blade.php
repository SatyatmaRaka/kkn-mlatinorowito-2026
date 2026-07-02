<x-app-layout>
    <x-slot name="header">
        Tambah Program Kerja
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.proker.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        value="{{ old('judul') }}"
                        class="form-control @error('judul') is-invalid @enderror"
                        required
                    >
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tema" class="form-label">Tema</label>
                    <input
                        type="text"
                        id="tema"
                        name="tema"
                        value="{{ old('tema') }}"
                        class="form-control @error('tema') is-invalid @enderror"
                    >
                    @error('tema')
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

                <div class="mb-3">
                    <label for="icon" class="form-label">Icon</label>
                    <input
                        type="text"
                        id="icon"
                        name="icon"
                        value="{{ old('icon', '📋') }}"
                        placeholder="📋"
                        class="form-control @error('icon') is-invalid @enderror"
                    >
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="pic" class="form-label">PIC</label>
                    <input
                        type="text"
                        id="pic"
                        name="pic"
                        value="{{ old('pic') }}"
                        class="form-control @error('pic') is-invalid @enderror"
                    >
                    @error('pic')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select
                        id="status"
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >
                        <option value="Coming Soon" @selected(old('status', 'Coming Soon') === 'Coming Soon')>Coming Soon</option>
                        <option value="Aktif" @selected(old('status') === 'Aktif')>Aktif</option>
                    </select>
                    @error('status')
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
                    <a href="{{ route('admin.proker.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
