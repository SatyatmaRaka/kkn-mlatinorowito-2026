<x-layouts.public>
    <section class="py-5 bg-light">
        <div class="container px-3 px-md-5">
            <a href="{{ url('/#proker') }}" class="btn btn-link text-decoration-none ps-0 mb-4">
                &larr; Kembali ke Program Kerja
            </a>

            <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="fs-1 mb-3">{{ $programKerja->icon ?? '📋' }}</div>

                        <h1 class="h2 fw-bold mb-2">{{ $programKerja->judul }}</h1>

                        @if ($programKerja->tema)
                            <p class="text-muted mb-3">{{ $programKerja->tema }}</p>
                        @endif

                        @if ($programKerja->status === 'Aktif')
                            <span class="badge bg-success">{{ $programKerja->status }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $programKerja->status }}</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h2 class="h6 fw-semibold text-uppercase text-muted mb-2">Deskripsi</h2>
                        <p class="mb-0">{{ $programKerja->deskripsi }}</p>
                    </div>

                    <div class="mb-5">
                        <h2 class="h6 fw-semibold text-uppercase text-muted mb-2">Penanggung Jawab (PIC)</h2>
                        <p class="mb-0">{{ $programKerja->pic ?? 'Belum ditentukan' }}</p>
                    </div>

                    <div>
                        <h2 class="h6 fw-semibold text-uppercase text-muted mb-3">Dokumentasi Foto</h2>
                        <div
                            class="rounded d-flex align-items-center justify-content-center text-white"
                            style="height: 220px; background: linear-gradient(135deg, var(--umk-blue) 0%, #2d7ab8 100%);"
                        >
                            <span class="text-white-50">Dokumentasi akan segera hadir.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
