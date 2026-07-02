<x-layouts.public>
    <x-slot:title>Arsip Kegiatan - KKN Mlatinorowito 2026</x-slot:title>

    <section class="py-5 bg-light" style="padding-top: 100px !important;">
        <div class="container px-3 px-md-5">
            <a href="{{ url('/#kegiatan-galeri') }}"
               class="d-inline-flex align-items-center gap-2 text-decoration-none mb-4 px-4 py-2 rounded-pill fw-semibold"
               style="background:#1a2e4a; color:#fff; font-size:0.9rem;">
                <span>&#8592;</span> Kembali ke Beranda
            </a>

            <div class="text-center mb-5">
                <h1 class="display-6 fw-bold mb-2">Arsip Kegiatan Harian</h1>
                <p class="text-muted mb-0">Seluruh dokumentasi kegiatan KKN Mlatinorowito 2026</p>
            </div>

            <div class="row g-4">
                @forelse ($kegiatan as $item)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="premium-card h-100 overflow-hidden">
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-100 object-fit-cover" style="height: 200px;">
                            @else
                                <div style="height: 200px; background: linear-gradient(135deg, #1a5c99 0%, #2d7ab8 100%);"></div>
                            @endif
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="badge bg-primary align-self-start mb-2">
                                    {{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}
                                </span>
                                <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>
                                <p class="text-muted small flex-grow-1 mb-3">{{ Str::limit($item->deskripsi_singkat, 120) }}</p>
                                <a href="{{ route('detail.kegiatan', $item) }}" class="btn btn-sm btn-outline-primary w-100 mt-auto">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-5 bg-white rounded-4 shadow-sm border mb-0">Belum ada kegiatan yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>

            @if ($kegiatan->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $kegiatan->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
