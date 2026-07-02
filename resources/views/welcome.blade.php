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
                padding-top: 100px;
                padding-bottom: 4rem;
                background: linear-gradient(135deg, var(--umk-blue) 0%, #1e3a8a 50%, var(--umk-blue-accent) 100%);
            }

            .hero-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1.5px, transparent 1.5px);
                background-size: 32px 32px;
                pointer-events: none;
            }
            
            .hero-section::after {
                content: '';
                position: absolute;
                width: 80vw;
                height: 80vw;
                max-width: 800px;
                max-height: 800px;
                background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 70%);
                top: -20%;
                right: -10%;
                border-radius: 50%;
                pointer-events: none;
            }

            .hero-content {
                position: relative;
                z-index: 1;
            }

            .hero-title {
                font-size: clamp(2.5rem, 8vw, 4.5rem);
                letter-spacing: -0.03em;
                line-height: 1.1;
                text-shadow: 0 10px 30px rgba(0,0,0,0.3);
            }

            .section-title {
                color: var(--umk-blue);
                font-weight: 800;
                letter-spacing: -0.02em;
            }

            .section-title-accent {
                width: 80px;
                height: 6px;
                background: linear-gradient(90deg, var(--umk-yellow), var(--umk-yellow-hover));
                margin: 1rem auto 0;
                border-radius: 10px;
            }

            .stat-number {
                background: linear-gradient(135deg, var(--umk-blue), var(--umk-blue-accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                font-size: clamp(2.5rem, 6vw, 3.5rem);
                font-weight: 800;
                line-height: 1.2;
            }

            .proker-icon {
                font-size: 3rem;
                line-height: 1;
                filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
                transition: transform 0.3s ease;
            }
            
            .premium-card:hover .proker-icon {
                transform: scale(1.1) rotate(5deg);
            }

            .kegiatan-photo-placeholder {
                height: 220px;
            }

            .avatar-circle {
                width: 100px;
                height: 100px;
                font-size: 2rem;
                font-weight: 700;
                color: #fff;
                box-shadow: 0 10px 20px -5px rgba(0,0,0,0.2);
            }

            .galeri-item {
                aspect-ratio: 1 / 1;
                overflow: hidden;
                border-radius: 1rem;
                cursor: pointer;
                position: relative;
                box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1);
            }

            .galeri-item-inner {
                width: 100%;
                height: 100%;
                transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .galeri-item:hover .galeri-item-inner {
                transform: scale(1.1);
            }

            .galeri-item-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(15,23,42,0.8), transparent);
                display: flex;
                align-items: flex-end;
                justify-content: center;
                opacity: 0;
                padding-bottom: 1.5rem;
                transition: all 0.3s ease;
            }

            .galeri-item:hover .galeri-item-overlay {
                opacity: 1;
            }

            /* Lightbox styles */
            .galeri-lightbox { z-index: 2000; }
            .galeri-lightbox-backdrop { z-index: 1; }
            .galeri-lightbox-image {
                z-index: 2;
                max-width: min(90vw, 800px);
                max-height: 85vh;
                border-radius: 1rem;
            }
            .galeri-lightbox-close, .galeri-lightbox-nav {
                z-index: 3;
                pointer-events: auto;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255,255,255,0.2);
            }
            .galeri-lightbox-nav {
                width: 56px; height: 56px;
                border-radius: 50%;
                color: #fff; font-size: 2rem;
                display: flex; align-items: center; justify-content: center;
                transition: all 0.3s ease;
                top: 50%; transform: translateY(-50%);
            }
            .galeri-lightbox-nav:hover, .galeri-lightbox-close:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: translateY(-50%) scale(1.1);
            }
            .galeri-lightbox-close {
                top: 1.5rem; right: 1.5rem;
                width: 48px; height: 48px;
                border-radius: 50%;
                color: #fff; font-size: 2rem;
                transform: none;
            }
            .galeri-lightbox-close:hover {
                transform: scale(1.1);
            }

            .kontak-map {
                border: 0;
                border-radius: 1.25rem;
                width: 100%;
                height: 400px;
                box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
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
                        class="premium-card text-center p-5"
                        x-data="{
                            count: 0,
                            target: {{ count($anggota) }},
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
                        class="premium-card text-center p-5"
                        x-data="{
                            count: 0,
                            target: {{ count($programKerja) }},
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
                        class="premium-card text-center p-5"
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
                Universitas Muria Kudus &middot; Periode {{ $pengaturan['periode_kkn'] ?? 'Juli - Agustus 2026' }} &middot; DPL: {{ $pengaturan['nama_dpl'] ?? '[Nama DPL akan diupdate]' }}
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
                @forelse ($anggota as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="premium-card h-100">
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
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted mb-0">
                            Data anggota akan segera ditampilkan.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Section 5: Program Kerja --}}
    <section id="proker" class="py-5">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Program Kerja</h2>
                <div class="section-title-accent"></div>
            </div>

            <div class="row g-4">
                @forelse ($programKerja as $item)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="premium-card h-100 position-relative">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="position-absolute top-0 end-0 m-3">
                                    @if ($item->status === 'Aktif')
                                        <span class="badge bg-success">{{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                    @endif
                                </div>

                                <div class="proker-icon mb-3 text-center pt-2">
                                    {{ $item->icon ?? '📋' }}
                                </div>

                                <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>
                                <p class="text-muted small flex-grow-1 mb-3">
                                    {{ Str::limit($item->deskripsi, 120) }}
                                </p>

                                <a
                                    href="{{ route('detail.proker', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary w-100 mt-auto"
                                >
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted mb-0">
                            Program kerja akan segera diumumkan.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Section 6: Kegiatan & Galeri --}}
    <section id="kegiatan-galeri" class="py-5 bg-light" x-data="{ tab: 'kegiatan' }">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Kegiatan & Galeri</h2>
                <div class="section-title-accent"></div>
            </div>

            <!-- Tabs Navigation -->
            <div class="d-flex justify-content-center mb-5">
                <div class="bg-white rounded-pill p-1 shadow-sm d-inline-flex" style="border: 1px solid rgba(0,0,0,0.05);">
                    <button 
                        @click="tab = 'kegiatan'" 
                        class="btn rounded-pill px-4 py-2 fw-medium border-0 transition-all"
                        :class="tab === 'kegiatan' ? 'btn-primary shadow-sm text-white' : 'bg-transparent text-muted'"
                        style="transition: all 0.3s ease;"
                    >
                        Agenda Kegiatan
                    </button>
                    <button 
                        @click="tab = 'galeri'" 
                        class="btn rounded-pill px-4 py-2 fw-medium border-0 transition-all"
                        :class="tab === 'galeri' ? 'btn-primary shadow-sm text-white' : 'bg-transparent text-muted'"
                        style="transition: all 0.3s ease;"
                    >
                        Galeri Foto
                    </button>
                </div>
            </div>

            <!-- Tab Content: Kegiatan -->
            <div x-show="tab === 'kegiatan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @php
                    $kegiatanGradients = [
                        'linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%)',
                        'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
                        'linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%)',
                        'linear-gradient(135deg, #0f172a 0%, #047857 100%)',
                        'linear-gradient(135deg, #7e22ce 0%, #be123c 100%)',
                    ];
                @endphp

                <div class="row g-4 mb-4">
                    @forelse ($kegiatan as $item)
                        <div class="col-12 col-md-4">
                            <div class="premium-card h-100 overflow-hidden">
                                <div
                                    class="kegiatan-photo-placeholder d-flex align-items-center justify-content-center text-white"
                                    style="background: {{ $kegiatanGradients[$loop->index % count($kegiatanGradients)] }};"
                                >
                                    <span class="fs-1 opacity-50">📷</span>
                                </div>

                                <div class="card-body p-4 d-flex flex-column">
                                    <span class="badge bg-primary align-self-start mb-2">
                                        {{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}
                                    </span>

                                    <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>
                                    <p class="text-muted small flex-grow-1 mb-3">
                                        {{ Str::limit($item->deskripsi_singkat, 120) }}
                                    </p>

                                    <a
                                        href="{{ route('detail.kegiatan', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary w-100 mt-auto"
                                    >
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted mb-0 py-5 bg-white rounded-4 shadow-sm border">
                                Kegiatan akan terus diupdate selama masa KKN berlangsung.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content: Galeri -->
            <div x-show="tab === 'galeri'" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @php
                    $galeriPhotoUrls = $galeri->map(fn ($item) => asset('storage/' . $item->foto))->values();
                @endphp

                @if ($galeri->isEmpty())
                    <p class="text-center text-muted mb-0 py-5 bg-white rounded-4 shadow-sm border">
                        Dokumentasi kegiatan akan segera hadir.
                    </p>
                @else
                <div
                    x-data="{
                        lightboxOpen: false,
                        currentIndex: 0,
                        photos: @js($galeriPhotoUrls),
                        openLightbox(index) {
                            this.currentIndex = index;
                            this.lightboxOpen = true;
                            document.body.style.overflow = 'hidden';
                        },
                        closeLightbox() {
                            this.lightboxOpen = false;
                            document.body.style.overflow = '';
                        },
                        nextPhoto() {
                            this.currentIndex = (this.currentIndex + 1) % this.photos.length;
                        },
                        prevPhoto() {
                            this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
                        }
                    }"
                    @keydown.escape.window="lightboxOpen && closeLightbox()"
                    @keydown.arrow-right.window="lightboxOpen && nextPhoto()"
                    @keydown.arrow-left.window="lightboxOpen && prevPhoto()"
                >
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        @foreach ($galeri as $foto)
                            <div class="col">
                                <div
                                    class="galeri-item shadow-sm"
                                    @click="openLightbox({{ $loop->index }})"
                                    role="button"
                                    tabindex="0"
                                    @keydown.enter="openLightbox({{ $loop->index }})"
                                >
                                    <img
                                        src="{{ asset('storage/' . $foto->foto) }}"
                                        alt="{{ $foto->keterangan ?? 'Foto galeri' }}"
                                        class="galeri-item-inner w-100 h-100 object-fit-cover"
                                    >
                                    <div class="galeri-item-overlay">
                                        <span class="fs-2 text-white" aria-hidden="true">👁️</span>
                                    </div>
                                </div>
                                @if ($foto->keterangan)
                                    <p class="small text-muted text-center mt-2 mb-0 fw-medium">{{ $foto->keterangan }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Lightbox --}}
                    <template x-teleport="body">
                        <div
                            x-show="lightboxOpen"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="galeri-lightbox position-fixed top-0 start-0 w-100 h-100"
                            style="background: rgba(15, 23, 42, 0.95);"
                            x-cloak
                        >
                            <div
                                class="galeri-lightbox-backdrop position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                @click.self="closeLightbox()"
                            >
                                <button
                                    type="button"
                                    class="galeri-lightbox-close position-absolute border-0"
                                    @click.stop="closeLightbox()"
                                    aria-label="Tutup"
                                >&times;</button>

                                <button
                                    type="button"
                                    class="galeri-lightbox-nav position-absolute start-0 ms-3 ms-md-5 border-0"
                                    @click.stop="prevPhoto()"
                                    aria-label="Foto sebelumnya"
                                >&lsaquo;</button>

                                <img
                                    :src="photos[currentIndex]"
                                    alt="Foto galeri"
                                    class="galeri-lightbox-image shadow-lg object-fit-contain bg-transparent"
                                    @click.stop
                                >

                                <button
                                    type="button"
                                    class="galeri-lightbox-nav position-absolute end-0 me-3 me-md-5 border-0"
                                    @click.stop="nextPhoto()"
                                    aria-label="Foto berikutnya"
                                >&rsaquo;</button>
                            </div>
                        </div>
                    </template>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Section 8: Kontak & Lokasi --}}
    <section id="kontak" class="py-5 bg-light">
        @php
            $kontakInstagram = $pengaturan['instagram'] ?? '@kknumk.mlatinorowito.26';
            $kontakInstagramUrl = 'https://www.instagram.com/kknumk.mlatinorowito.26?igsh=MXM2ZGpiNzh5NTcxbg==';
            $kontakInstagramLabel = str_starts_with($kontakInstagram, '@') ? $kontakInstagram : '@' . ltrim($kontakInstagram, '@');
        @endphp
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Kontak & Lokasi</h2>
                <div class="section-title-accent"></div>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-md-6">
                    <div class="h-100 p-4 bg-white rounded shadow-sm">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex gap-3 mb-4">
                                <span class="fs-4" aria-hidden="true">📍</span>
                                <div>
                                    <div class="fw-semibold mb-1">KKN UMK 2026 - Kelurahan Mlatinorowito</div>
                                    <div class="text-muted">Kelurahan Mlatinorowito, Kecamatan Kota, Kabupaten Kudus</div>
                                </div>
                            </li>

                            <li class="d-flex gap-3 mb-0">
                                <span class="fs-4" aria-hidden="true">📷</span>
                                <div>
                                    <div class="fw-semibold mb-1">Instagram</div>
                                    <a
                                        href="{{ $kontakInstagramUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-decoration-none"
                                    >
                                        {{ $kontakInstagramLabel }}
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <iframe
                        class="kontak-map shadow-sm"
                        src="https://maps.google.com/maps?q=Kelurahan%20Mlatinorowito,%20Kudus&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Kelurahan Mlatinorowito, Kudus"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
