<x-layouts.public>
    <section class="py-5 bg-light">
        <div class="container px-3 px-md-5">
            <a href="{{ url('/#kegiatan') }}" class="btn btn-link text-decoration-none ps-0 mb-4">
                &larr; Kembali ke Kegiatan Harian
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
