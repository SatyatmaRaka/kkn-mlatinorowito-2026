<x-layouts.public>
    <x-slot:title>{{ $programKerja->judul }} - KKN Mlatinorowito 2026</x-slot:title>
    <x-slot:description>{{ Str::limit(strip_tags($programKerja->deskripsi), 150) }}</x-slot:description>
    <section class="py-5 bg-light" style="padding-top: 100px !important;">
        <div class="container px-3 px-md-5">
            <a href="{{ url('/#proker') }}"
               class="d-inline-flex align-items-center gap-2 text-decoration-none mb-4 px-4 py-2 rounded-pill fw-semibold"
               style="background:#1a2e4a; color:#fff; font-size:0.9rem; transition: background 0.2s, transform 0.15s;"
               onmouseover="this.style.background='#0d1f33'; this.style.transform='translateX(-3px)'"
               onmouseout="this.style.background='#1a2e4a'; this.style.transform='translateX(0)'">
                <span style="font-size:1rem;">&#8592;</span> Kembali ke Program Kerja
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
                        <p class="mb-0">{{ $programKerja->deskripsi ?: 'Deskripsi program kerja akan segera diumumkan.' }}</p>
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
