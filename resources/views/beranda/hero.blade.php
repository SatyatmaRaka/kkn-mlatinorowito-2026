    {{-- Section 1: Hero / Beranda --}}
    <section id="beranda" class="hero-section">
        <div class="container px-3 px-md-5 hero-content">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8 text-center text-white">
                    <h1 class="hero-title fw-bold mb-3">{{ $pengaturan['nama_kelompok'] ?? 'KKN UMK 2026' }}</h1>
                    <p class="fs-5 fs-lg-4 mb-2">Kelurahan Mlatinorowito, Kec. Kota, Kab. Kudus</p>
                    <p class="lead mb-4 px-md-3">
                        {{ $pengaturan['tagline'] ?? 'Berdampak dalam Membangun Desa Mandiri dan Berkelanjutan' }}
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-stretch align-items-sm-center">
                        <a href="#proker" class="btn btn-umk-yellow shadow-lg me-sm-2 mb-2 mb-sm-0">
                            Lihat Program Kami
                        </a>
                        <a href="#anggota" class="btn btn-outline-light rounded-pill px-4 ms-sm-2">
                            Kenali Tim Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
