<x-app-layout>
    <x-slot name="header">
        Upload Foto Galeri
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form id="galeri-upload-form" action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="foto" class="form-label">Pilih Foto</label>
                    <input
                        type="file"
                        id="foto"
                        name="foto[]"
                        accept="image/*"
                        multiple
                        class="form-control @error('foto') is-invalid @enderror @error('foto.*') is-invalid @enderror"
                        required
                    >
                    @error('foto')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('foto.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Anda dapat memilih beberapa foto sekaligus (maks. 2 MB per foto).</div>
                </div>

                <div id="preview-container" class="row row-cols-2 row-cols-md-4 g-3 mb-4 d-none"></div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const fileInput = document.getElementById('foto');
                const previewContainer = document.getElementById('preview-container');

                fileInput.addEventListener('change', function () {
                    previewContainer.innerHTML = '';
                    previewContainer.classList.add('d-none');

                    if (!fileInput.files.length) {
                        return;
                    }

                    previewContainer.classList.remove('d-none');

                    Array.from(fileInput.files).forEach(function (file) {
                        const col = document.createElement('div');
                        col.className = 'col';

                        const card = document.createElement('div');
                        card.className = 'border rounded p-2 h-100';

                        const img = document.createElement('img');
                        img.className = 'img-fluid rounded w-100 mb-2';
                        img.style.aspectRatio = '1 / 1';
                        img.style.objectFit = 'cover';
                        img.alt = file.name;

                        const label = document.createElement('label');
                        label.className = 'form-label small mb-1';
                        label.textContent = 'Keterangan (opsional)';

                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = 'keterangan[]';
                        input.className = 'form-control form-control-sm';
                        input.placeholder = 'Keterangan foto';

                        card.appendChild(img);
                        card.appendChild(label);
                        card.appendChild(input);
                        col.appendChild(card);
                        previewContainer.appendChild(col);

                        const reader = new FileReader();
                        reader.onload = function (event) {
                            img.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
