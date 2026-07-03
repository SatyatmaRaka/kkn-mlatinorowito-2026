    {{-- Section 8: Kontak & Lokasi --}}
    <section id="kontak" class="py-5 bg-light">
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
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Kontak & Lokasi</h2>
                <div class="section-title-accent"></div>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-md-6">
                    <div class="h-100 p-4 bg-white rounded shadow-sm">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-4">
                                <div>
                                    <div class="fw-semibold mb-1">KKN UMK 2026 - Kelurahan Mlatinorowito</div>
                                    <div class="text-muted">{{ $kontakAlamat }}</div>
                                </div>
                            </li>

                            @if ($kontakEmail)
                                <li class="mb-4">
                                    <div>
                                        <div class="fw-semibold mb-1">Email</div>
                                        <a href="mailto:{{ $kontakEmail }}" class="text-decoration-none">{{ $kontakEmail }}</a>
                                    </div>
                                </li>
                            @endif

                            <li class="mb-4">
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

                            <li class="mb-0">
                                <div>
                                    <div class="fw-semibold mb-1">TikTok</div>
                                    <a
                                        href="{{ $kontakTiktokUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-decoration-none"
                                    >
                                        {{ $kontakTiktokLabel }}
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <iframe
                        class="kontak-map shadow-sm"
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
