<x-layouts.public>
    <x-slot:title>{{ $kegiatan->judul }} - KKN Mlatinorowito 2026</x-slot:title>
    <x-slot:description>{{ $kegiatan->deskripsi_singkat }}</x-slot:description>
    <section class="py-5 bg-light" style="padding-top: 100px !important;">
        <div class="container px-3 px-md-5">
            <a href="{{ url('/#kegiatan') }}"
               class="d-inline-flex align-items-center gap-2 text-decoration-none mb-4 px-4 py-2 rounded-pill fw-semibold"
               style="background:#1a2e4a; color:#fff; font-size:0.9rem; transition: background 0.2s, transform 0.15s;"
               onmouseover="this.style.background='#0d1f33'; this.style.transform='translateX(-3px)'"
               onmouseout="this.style.background='#1a2e4a'; this.style.transform='translateX(0)'">
                <span style="font-size:1rem;">&#8592;</span> Kembali ke Kegiatan Harian
            </a>

            <div class="card border-0 shadow-sm mx-auto overflow-hidden" style="max-width: 800px;">
                @if ($kegiatan->foto)
                    <img
                        src="{{ asset('storage/' . $kegiatan->foto) }}"
                        alt="{{ $kegiatan->judul }}"
                        class="w-100 object-fit-cover"
                        style="height: 300px;"
                    >
                @else
                    <div
                        class="d-flex align-items-center justify-content-center text-white"
                        style="height: 300px; background: linear-gradient(135deg, #1a5c99 0%, #2d7ab8 100%);"
                    >
                        <span class="fs-1 opacity-50">📷</span>
                    </div>
                @endif

                <div class="card-body p-4 p-md-5">
                    <span class="badge bg-primary mb-3">
                        {{ $kegiatan->tanggal->locale('id')->translatedFormat('d F Y') }}
                    </span>

                    <h1 class="h2 fw-bold mb-4">{{ $kegiatan->judul }}</h1>

                    <div class="detail-kegiatan-konten">
                        @if ($kegiatan->konten)
                            {!! $kegiatan->konten !!}
                        @elseif ($kegiatan->deskripsi_singkat)
                            <p class="mb-0">{{ $kegiatan->deskripsi_singkat }}</p>
                        @else
                            <p class="mb-0 text-muted">Konten belum tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
