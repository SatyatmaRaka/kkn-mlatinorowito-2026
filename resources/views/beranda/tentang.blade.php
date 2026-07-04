    {{-- Section 3: Tentang Kelompok --}}
    <section id="tentang" class="public-section public-section-white">
        <div class="container px-3 px-md-5">
            <div class="section-header">
                <span class="section-eyebrow">Profil Kelompok</span>
                <h2 class="section-title h2">Tentang Kami</h2>
                <div class="section-title-accent"></div>
                <p class="section-lead">
                    Kelompok KKN UMK 2026 Kelurahan Mlatinorowito hadir untuk mendampingi masyarakat dalam mewujudkan desa yang mandiri, inovatif, dan berkelanjutan melalui program pengabdian terpadu.
                </p>
            </div>

            <div class="row justify-content-center mb-5">
                <div class="col-12 col-lg-8 text-center text-muted">
                    <p class="mb-0">
                        Kami berkomitmen menghadirkan solusi nyata di bidang pendidikan, ekonomi kreatif, lingkungan, dan pemberdayaan sosial agar dampak pengabdian dapat dirasakan secara langsung oleh warga.
                    </p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-12 col-md-4">
                    <div
                        class="premium-card stat-card text-center p-5"
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
                        <div class="text-muted fw-medium mt-2">Anggota</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div
                        class="premium-card stat-card text-center p-5"
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
                        <div class="text-muted fw-medium mt-2">Program Kerja</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div
                        class="premium-card stat-card text-center p-5"
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
                        <div class="text-muted fw-medium mt-2">Pengabdian</div>
                    </div>
                </div>
            </div>

            <p class="text-center public-meta-line mb-0">
                Universitas Muria Kudus &middot; Periode {{ $pengaturan['periode_kkn'] ?? 'Juli - Agustus 2026' }} &middot; DPL: {{ $pengaturan['nama_dpl'] ?? '[Nama DPL akan diupdate]' }}
            </p>
        </div>
    </section>
