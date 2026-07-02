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
                                    <span class="fs-1 opacity-50">­ƒôÀ</span>
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

                @if ($kegiatan->isNotEmpty())
                    <div class="text-center">
                        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-primary rounded-pill px-4">Lihat Semua Kegiatan</a>
                    </div>
                @endif
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
                                        <span class="fs-2 text-white" aria-hidden="true">­ƒæü´©Å</span>
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
