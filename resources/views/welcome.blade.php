<x-layouts.public>
    <style>
            [x-cloak] {
                display: none !important;
            }

            .hero-section {
                position: relative;
                min-height: 100vh;
                display: flex;
                align-items: center;
                overflow: hidden;
                padding-top: 72px;
                padding-bottom: 3rem;
                background: linear-gradient(135deg, var(--umk-blue) 0%, #1a5c99 55%, #2d7ab8 100%);
            }

            .hero-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: radial-gradient(rgba(255, 255, 255, 0.14) 1.5px, transparent 1.5px);
                background-size: 24px 24px;
                pointer-events: none;
            }

            .hero-content {
                position: relative;
                z-index: 1;
            }

            .btn-umk-yellow {
                background-color: var(--umk-yellow);
                border-color: var(--umk-yellow);
                color: var(--umk-blue);
                font-weight: 600;
            }

            .btn-umk-yellow:hover,
            .btn-umk-yellow:focus {
                background-color: #e6c200;
                border-color: #e6c200;
                color: var(--umk-blue);
            }

            .hero-title {
                font-size: clamp(2rem, 6vw, 3.5rem);
            }
    </style>

    {{-- Section 1: Hero / Beranda --}}
    <section id="beranda" class="hero-section">
        <div class="container px-3 px-md-5 hero-content">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8 text-center text-white">
                    <h1 class="hero-title fw-bold mb-3">KKN UMK 2026</h1>
                    <p class="fs-5 fs-lg-4 mb-2">Kelurahan Mlatinorowito, Kec. Kota, Kab. Kudus</p>
                    <p class="lead mb-4 px-md-3">
                        Berdampak dalam Membangun Desa Mandiri dan Berkelanjutan
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-stretch align-items-sm-center">
                        <a href="#proker" class="btn btn-umk-yellow btn-lg px-4">
                            Lihat Program Kami
                        </a>
                        <a href="#anggota" class="btn btn-outline-light btn-lg px-4">
                            Kenali Tim Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section berikutnya akan ditambahkan di sini --}}
</x-layouts.public>
