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

            .section-title {
                color: var(--umk-blue);
                font-weight: 700;
            }

            .section-title-accent {
                width: 64px;
                height: 4px;
                background-color: var(--umk-yellow);
                margin: 0.75rem auto 0;
                border-radius: 2px;
            }

            .stat-card {
                background: #fff;
                border-radius: 0.75rem;
                box-shadow: 0 0.25rem 1rem rgba(0, 51, 102, 0.08);
                padding: 2rem 1.5rem;
                height: 100%;
            }

            .stat-number {
                color: var(--umk-blue);
                font-size: clamp(2rem, 5vw, 2.75rem);
                font-weight: 700;
                line-height: 1.2;
            }

            .anggota-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .anggota-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 0.75rem 1.5rem rgba(0, 51, 102, 0.12) !important;
            }

            .avatar-circle {
                width: 80px;
                height: 80px;
                font-size: 1.5rem;
                font-weight: 700;
                color: #fff;
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

    {{-- Section 3: Tentang Kelompok --}}
    <section id="tentang" class="py-5">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Tentang Kami</h2>
                <div class="section-title-accent"></div>
            </div>

            <div class="row justify-content-center mb-5">
                <div class="col-12 col-lg-8 text-center text-muted">
                    <p class="mb-3">
                        Kelompok KKN UMK 2026 Kelurahan Mlatinorowito hadir untuk mendampingi masyarakat dalam mewujudkan desa yang mandiri, inovatif, dan berkelanjutan melalui program pengabdian terpadu.
                    </p>
                    <p class="mb-0">
                        Kami berkomitmen menghadirkan solusi nyata di bidang pendidikan, ekonomi kreatif, lingkungan, dan pemberdayaan sosial agar dampak pengabdian dapat dirasakan secara langsung oleh warga.
                    </p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-12 col-md-4">
                    <div
                        class="stat-card text-center"
                        x-data="{
                            count: 0,
                            target: 12,
                            started: false,
                            init() {
                                const observer = new IntersectionObserver((entries) => {
                                    if (entries[0].isIntersecting && !this.started) {
                                        this.started = true;
                                        this.animate();
                                    }
                                }, { threshold: 0.3 });
                                observer.observe(this.$el);
                            },
                            animate() {
                                const duration = 1500;
                                const start = performance.now();
                                const step = (now) => {
                                    const progress = Math.min((now - start) / duration, 1);
                                    this.count = Math.floor(progress * this.target);
                                    if (progress < 1) {
                                        requestAnimationFrame(step);
                                    } else {
                                        this.count = this.target;
                                    }
                                };
                                requestAnimationFrame(step);
                            }
                        }"
                    >
                        <div class="stat-number" x-text="count">0</div>
                        <div class="text-muted fw-medium">Anggota</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div
                        class="stat-card text-center"
                        x-data="{
                            count: 0,
                            target: 5,
                            started: false,
                            init() {
                                const observer = new IntersectionObserver((entries) => {
                                    if (entries[0].isIntersecting && !this.started) {
                                        this.started = true;
                                        this.animate();
                                    }
                                }, { threshold: 0.3 });
                                observer.observe(this.$el);
                            },
                            animate() {
                                const duration = 1500;
                                const start = performance.now();
                                const step = (now) => {
                                    const progress = Math.min((now - start) / duration, 1);
                                    this.count = Math.floor(progress * this.target);
                                    if (progress < 1) {
                                        requestAnimationFrame(step);
                                    } else {
                                        this.count = this.target;
                                    }
                                };
                                requestAnimationFrame(step);
                            }
                        }"
                    >
                        <div class="stat-number" x-text="count">0</div>
                        <div class="text-muted fw-medium">Program Kerja</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div
                        class="stat-card text-center"
                        x-data="{
                            count: 0,
                            target: 1,
                            started: false,
                            init() {
                                const observer = new IntersectionObserver((entries) => {
                                    if (entries[0].isIntersecting && !this.started) {
                                        this.started = true;
                                        this.animate();
                                    }
                                }, { threshold: 0.3 });
                                observer.observe(this.$el);
                            },
                            animate() {
                                const duration = 1500;
                                const start = performance.now();
                                const step = (now) => {
                                    const progress = Math.min((now - start) / duration, 1);
                                    this.count = Math.floor(progress * this.target);
                                    if (progress < 1) {
                                        requestAnimationFrame(step);
                                    } else {
                                        this.count = this.target;
                                    }
                                };
                                requestAnimationFrame(step);
                            }
                        }"
                    >
                        <div class="stat-number"><span x-text="count">0</span> Bulan</div>
                        <div class="text-muted fw-medium">Pengabdian</div>
                    </div>
                </div>
            </div>

            <p class="text-center text-muted mb-0 small">
                Universitas Muria Kudus &middot; Periode Juli - Agustus 2026 &middot; DPL: [Nama DPL akan diupdate]
            </p>
        </div>
    </section>

    {{-- Section 4: Anggota Tim --}}
    <section id="anggota" class="py-5 bg-light">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Anggota Tim</h2>
                <div class="section-title-accent"></div>
            </div>

            @php
                $avatarColors = ['#003366', '#1a5c99', '#2d7ab8', '#e67e22', '#27ae60', '#8e44ad', '#c0392b', '#16a085'];
            @endphp

            <div class="row g-4">
                @foreach ($anggota as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm anggota-card">
                            <div class="card-body text-center p-4">
                                <div
                                    class="avatar-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="background-color: {{ $avatarColors[$loop->index % count($avatarColors)] }};"
                                >
                                    {{ strtoupper(substr($item->nama, 0, 2)) }}
                                </div>

                                <h5 class="fw-bold mb-1">{{ $item->nama }}</h5>
                                <p class="text-muted small mb-2">{{ $item->jurusan }}</p>

                                @if ($item->jabatan === 'Koordinator Desa')
                                    <span class="badge bg-warning text-dark mb-3">{{ $item->jabatan }}</span>
                                @else
                                    <span class="badge bg-secondary mb-3">{{ $item->jabatan }}</span>
                                @endif

                                <a
                                    href="{{ route('detail.anggota', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary w-100"
                                >
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>
