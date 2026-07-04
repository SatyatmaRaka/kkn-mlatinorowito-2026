    {{-- Section 1: Hero / Beranda --}}
    <section id="beranda" class="hero-section">
        <img
            src="{{ asset('images/hero-kelurahan-mlatinorowito.jpg') }}"
            alt="Kantor Kelurahan Mlatinorowito, lokasi KKN Mlatinorowito 2026"
            class="visually-hidden"
            fetchpriority="high"
        >
        <div class="container px-3 px-md-5 hero-content">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-9 text-center text-white">
                    <span class="hero-badge">Universitas Muria Kudus · KKN 2026</span>

                    <h1 class="hero-title fw-bold mb-3">{{ $pengaturan['nama_kelompok'] ?? 'KKN UMK 2026' }}</h1>

                    <p class="hero-location mb-2">Kelurahan Mlatinorowito, Kec. Kota, Kab. Kudus</p>

                    <p class="hero-tagline mb-4 px-md-3">
                        {{ $pengaturan['tagline'] ?? 'Berdampak dalam Membangun Desa Mandiri dan Berkelanjutan' }}
                    </p>

                    <div class="hero-actions d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-stretch align-items-sm-center">
                        <a href="#proker" class="btn btn-umk-yellow shadow-lg">
                            Lihat Program Kami
                        </a>
                        <a href="#anggota" class="btn btn-outline-light rounded-pill">
                            Kenali Tim Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <a href="#tentang" class="hero-scroll-hint text-decoration-none" aria-label="Scroll ke bagian tentang">
            Scroll
            <span></span>
        </a>
    </section>
