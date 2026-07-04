    {{-- Section 8: Kontak & Lokasi --}}
    <section id="kontak" class="public-section public-section-alt">
        @php
            $kontakInstagramUrl = \App\Penunjang\TautanSosial::instagramUrl($pengaturan['instagram'] ?? null);
            $kontakInstagramLabel = \App\Penunjang\TautanSosial::instagramLabel($pengaturan['instagram'] ?? null);
            $kontakTiktokUrl = \App\Penunjang\TautanSosial::tiktokUrl($pengaturan['tiktok'] ?? null);
            $kontakTiktokLabel = \App\Penunjang\TautanSosial::tiktokLabel($pengaturan['tiktok'] ?? null);
            $kontakEmail = $pengaturan['email'] ?? null;
            $kontakAlamat = $pengaturan['alamat'] ?? 'Kelurahan Mlatinorowito, Kecamatan Kota, Kabupaten Kudus';
            $mapsUrl = \App\Penunjang\SematanPeta::safeUrl($pengaturan['maps_embed_url'] ?? null);
        @endphp
        <div class="container px-3 px-md-5">
            <div class="section-header">
                <span class="section-eyebrow">Hubungi Kami</span>
                <h2 class="section-title h2">Kontak & Lokasi</h2>
                <div class="section-title-accent"></div>
                <p class="section-lead">Informasi kontak resmi kelompok KKN dan lokasi Kantor Kelurahan Mlatinorowito.</p>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-md-6">
                    <div class="kontak-panel">
                        <div class="kontak-item">
                            <div class="kontak-item-label">Kelompok KKN</div>
                            <div class="fw-semibold mb-1">KKN UMK 2026 — Kelurahan Mlatinorowito</div>
                            <div class="text-muted">{{ $kontakAlamat }}</div>
                        </div>

                        @if ($kontakEmail)
                            <div class="kontak-item">
                                <div class="kontak-item-label">Email</div>
                                <a href="mailto:{{ $kontakEmail }}" class="text-decoration-none fw-medium">{{ $kontakEmail }}</a>
                            </div>
                        @endif

                        <div class="kontak-item">
                            <div class="kontak-item-label">Instagram</div>
                            <a
                                href="{{ $kontakInstagramUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-decoration-none fw-medium"
                            >
                                {{ $kontakInstagramLabel }}
                            </a>
                        </div>

                        <div class="kontak-item mb-0">
                            <div class="kontak-item-label">TikTok</div>
                            <a
                                href="{{ $kontakTiktokUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-decoration-none fw-medium"
                            >
                                {{ $kontakTiktokLabel }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <iframe
                        class="kontak-map"
                        src="{{ $mapsUrl }}"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Kelurahan Mlatinorowito, Kudus"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
