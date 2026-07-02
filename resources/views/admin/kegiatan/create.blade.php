<x-app-layout>
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.css" rel="stylesheet">
        <style>
            #editor-container {
                min-height: 220px;
                background: #fff;
            }
        </style>
    @endpush

    <x-slot name="header">
        Tambah Kegiatan
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form id="kegiatan-form" action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        value="{{ old('tanggal') }}"
                        class="form-control @error('tanggal') is-invalid @enderror"
                        required
                    >
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                    <textarea
                        id="deskripsi_singkat"
                        name="deskripsi_singkat"
                        rows="3"
                        class="form-control @error('deskripsi_singkat') is-invalid @enderror"
                    >{{ old('deskripsi_singkat') }}</textarea>
                    @error('deskripsi_singkat')
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

                <div class="mb-4">
                    <label class="form-label">Konten</label>
                    <div id="editor-container" class="@error('konten') border border-danger rounded @enderror"></div>
                    <input type="hidden" name="konten" id="konten" value="{{ old('konten') }}">
                    @error('konten')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
        <script>
            (function () {
                const kontenInput = document.getElementById('konten');
                const form = document.getElementById('kegiatan-form');

                if (typeof Quill !== 'undefined') {
                    const quill = new Quill('#editor-container', {
                        theme: 'snow',
                        placeholder: 'Tulis konten kegiatan di sini...',
                    });

                    if (kontenInput && kontenInput.value) {
                        quill.root.innerHTML = kontenInput.value;
                    }

                    quill.on('text-change', function () {
                        if (kontenInput) kontenInput.value = quill.root.innerHTML;
                    });

                    if (form) {
                        form.addEventListener('submit', function () {
                            if (kontenInput) kontenInput.value = quill.root.innerHTML;
                        });
                    }
                } else {
                    console.error('Quill is not loaded');
                }
            })();
        </script>
    @endpush
</x-app-layout>
