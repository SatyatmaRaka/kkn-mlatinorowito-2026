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

            .proker-card,
            .kegiatan-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .proker-card:hover,
            .kegiatan-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 0.75rem 1.5rem rgba(0, 51, 102, 0.12) !important;
            }

            .proker-icon {
                font-size: 2.5rem;
                line-height: 1;
            }

            .kegiatan-photo-placeholder {
                height: 200px;
            }

            .avatar-circle {
                width: 80px;
                height: 80px;
                font-size: 1.5rem;
                font-weight: 700;
                color: #fff;
            }

            .galeri-item {
                aspect-ratio: 1 / 1;
                overflow: hidden;
                border-radius: 0.5rem;
                cursor: pointer;
                position: relative;
            }

            .galeri-item-inner {
                width: 100%;
                height: 100%;
                transition: transform 0.35s ease;
            }

            .galeri-item:hover .galeri-item-inner {
                transform: scale(1.05);
            }

            .galeri-item-overlay {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.45);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.35s ease;
            }

            .galeri-item:hover .galeri-item-overlay {
                opacity: 1;
            }

            .galeri-lightbox {
                z-index: 2000;
            }

            .galeri-lightbox-backdrop {
                z-index: 1;
            }

            .galeri-lightbox-image {
                z-index: 2;
                max-width: min(90vw, 720px);
                max-height: 80vh;
                width: auto;
                height: auto;
            }

            .galeri-lightbox-close,
            .galeri-lightbox-nav {
                z-index: 3;
                pointer-events: auto;
            }

            .galeri-lightbox-nav {
                width: 48px;
                height: 48px;
                border: none;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                color: #fff;
                font-size: 1.75rem;
                line-height: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background-color 0.2s ease;
                top: 50%;
                transform: translateY(-50%);
            }

            .galeri-lightbox-nav:hover {
                background: rgba(255, 255, 255, 0.35);
            }

            .galeri-lightbox-close {
                top: 1rem;
                right: 1rem;
                width: 44px;
                height: 44px;
                border: none;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                color: #fff;
                font-size: 1.75rem;
                line-height: 1;
                transform: none;
            }

            .galeri-lightbox-close:hover {
                background: rgba(255, 255, 255, 0.35);
            }

            .kontak-map {
                border: 0;
                border-radius: 0.75rem;
                width: 100%;
                height: 350px;
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
                        class="stat-card text-center"
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
                        <div class="card h-100 border-0 shadow-sm proker-card position-relative">
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

    {{-- Section 6: Kegiatan Harian --}}
    <section id="kegiatan" class="py-5 bg-light">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Kegiatan Harian</h2>
                <div class="section-title-accent"></div>
            </div>

            @php
                $kegiatanGradients = [
                    'linear-gradient(135deg, #003366 0%, #1a5c99 100%)',
                    'linear-gradient(135deg, #1a5c99 0%, #2d7ab8 100%)',
                    'linear-gradient(135deg, #2d7ab8 0%, #5ba3d9 100%)',
                    'linear-gradient(135deg, #003366 0%, #27ae60 100%)',
                    'linear-gradient(135deg, #8e44ad 0%, #c0392b 100%)',
                ];
            @endphp

            @forelse ($kegiatan as $item)
                @if ($loop->first)
                    <div class="row g-4 mb-4">
                @endif

                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 shadow-sm kegiatan-card overflow-hidden">
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

                @if ($loop->last)
                    </div>
                    <p class="text-center text-muted mb-0">
                        Kegiatan akan terus diupdate selama masa KKN berlangsung.
                    </p>
                @endif
            @empty
                <p class="text-center text-muted mb-0">
                    Kegiatan akan terus diupdate selama masa KKN berlangsung.
                </p>
            @endforelse
        </div>
    </section>

    {{-- Section 7: Galeri Foto --}}
    <section id="galeri" class="py-5">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Galeri Foto</h2>
                <div class="section-title-accent"></div>
            </div>

            @php
                $galeriPhotoUrls = $galeri->map(fn ($item) => asset('storage/' . $item->foto))->values();
            @endphp

            @if ($galeri->isEmpty())
                <p class="text-center text-muted mb-0">
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
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-4">
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
                                <p class="small text-muted text-center mt-2 mb-0">{{ $foto->keterangan }}</p>
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
                        style="background: rgba(0, 0, 0, 0.92);"
                        x-cloak
                    >
                        <div
                            class="galeri-lightbox-backdrop position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                            @click.self="closeLightbox()"
                        >
                            <button
                                type="button"
                                class="galeri-lightbox-close position-absolute"
                                @click.stop="closeLightbox()"
                                aria-label="Tutup"
                            >&times;</button>

                            <button
                                type="button"
                                class="galeri-lightbox-nav position-absolute start-0 ms-3"
                                @click.stop="prevPhoto()"
                                aria-label="Foto sebelumnya"
                            >&lsaquo;</button>

                            <img
                                :src="photos[currentIndex]"
                                alt="Foto galeri"
                                class="galeri-lightbox-image rounded shadow-lg object-fit-contain bg-dark"
                                @click.stop
                            >

                            <button
                                type="button"
                                class="galeri-lightbox-nav position-absolute end-0 me-3"
                                @click.stop="nextPhoto()"
                                aria-label="Foto berikutnya"
                            >&rsaquo;</button>
                        </div>
                    </div>
                </template>
            </div>
            @endif
        </div>
    </section>

    {{-- Section 8: Kontak & Lokasi --}}
    <section id="kontak" class="py-5 bg-light">
        @php
            $kontakEmail = $pengaturan['email'] ?? 'kkn.mlatinorowito2026@gmail.com';
            $kontakInstagram = $pengaturan['instagram'] ?? '@kkn_mlatinorowito2026';
            $kontakInstagramHandle = ltrim($kontakInstagram, '@');
            $kontakInstagramUrl = str_starts_with($kontakInstagram, 'http')
                ? $kontakInstagram
                : 'https://instagram.com/' . $kontakInstagramHandle;
            $kontakInstagramLabel = str_starts_with($kontakInstagram, '@') ? $kontakInstagram : '@' . $kontakInstagramHandle;
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
                            <li class="d-flex gap-3 mb-4">
                                <span class="fs-4" aria-hidden="true">📧</span>
                                <div>
                                    <div class="fw-semibold mb-1">Email</div>
                                    <a href="mailto:{{ $kontakEmail }}" class="text-decoration-none">
                                        {{ $kontakEmail }}
                                    </a>
                                </div>
                            </li>
                            <li class="d-flex gap-3">
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
