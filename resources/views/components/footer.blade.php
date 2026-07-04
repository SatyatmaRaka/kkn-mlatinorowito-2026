@php
    use App\Penunjang\TautanSosial;

    $namaKelompok = $pengaturan['nama_kelompok'] ?? 'KKN UMK Mlatinorowito 2026';
    $tagline = $pengaturan['tagline'] ?? 'Berdampak dalam Membangun Desa Mandiri dan Berkelanjutan';
    $periode = $pengaturan['periode_kkn'] ?? 'Juli - Agustus 2026';
    $instagramUrl = TautanSosial::instagramUrl($pengaturan['instagram'] ?? null);
    $instagramLabel = TautanSosial::instagramLabel($pengaturan['instagram'] ?? null);
    $tiktokUrl = TautanSosial::tiktokUrl($pengaturan['tiktok'] ?? null);
    $tiktokLabel = TautanSosial::tiktokLabel($pengaturan['tiktok'] ?? null);
@endphp

<footer class="text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #001a3a 0%, #003366 50%, #001a3a 100%);">

    {{-- Decorative blobs --}}
    <div class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none; overflow: hidden;">
        <div style="position:absolute; width:400px; height:400px; border-radius:50%; background:rgba(255,255,255,0.03); top:-100px; left:-100px;"></div>
        <div style="position:absolute; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,0.03); bottom:-80px; right:-60px;"></div>
    </div>

    <div class="container px-4 px-md-5 position-relative" style="z-index:1;">

        {{-- Top divider line with gradient --}}
        <div style="height:1px; background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent); margin-bottom: 3rem;"></div>

        {{-- Main footer content --}}
        <div class="row g-5 pb-5">

            {{-- Brand column --}}
            <div class="col-12 col-md-5">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset('images/logo-kkn.png') }}" alt="Logo KKN Mlatinorowito 2026" class="rounded-circle" style="width: 48px; height: 48px; object-fit: contain;">
                    <div>
                        <div class="fw-bold fs-5 lh-sm" style="letter-spacing: -0.02em;">{{ $namaKelompok }}</div>
                        <div class="small opacity-50">{{ $periode }}</div>
                    </div>
                </div>
                <p class="text-white opacity-75 fw-light mb-4" style="line-height: 1.7; max-width: 320px;">
                    {{ $tagline }}
                </p>

                <div class="d-flex flex-wrap gap-3">
                    <a
                        href="{{ $instagramUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-white text-decoration-none small d-inline-flex align-items-center gap-2 opacity-75"
                    >
                        <i class="bi bi-instagram"></i> {{ $instagramLabel }}
                    </a>
                    <a
                        href="{{ $tiktokUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-white text-decoration-none small d-inline-flex align-items-center gap-2 opacity-75"
                    >
                        <i class="bi bi-tiktok"></i> {{ $tiktokLabel }}
                    </a>
                </div>
            </div>

            {{-- Divider on desktop --}}
            <div class="col-md-1 d-none d-md-flex justify-content-center">
                <div style="width:1px; background:rgba(255,255,255,0.1); height:100%;"></div>
            </div>

            {{-- Info column --}}
            <div class="col-12 col-md-6">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex gap-3 mb-4">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width:38px; height:38px; background:rgba(255,255,255,0.08);">
                            <i class="bi bi-calendar2-week-fill text-info fs-6"></i>
                        </div>
                        <div>
                            <div class="fw-semibold mb-1" style="font-size: 0.9rem;">Periode</div>
                            <div class="opacity-60 small">{{ $periode }}</div>
                        </div>
                    </li>
                    <li class="d-flex gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle" style="width:38px; height:38px; background:rgba(255,255,255,0.08);">
                            <i class="bi bi-mortarboard-fill text-success fs-6"></i>
                        </div>
                        <div>
                            <div class="fw-semibold mb-1" style="font-size: 0.9rem;">Universitas</div>
                            <div class="opacity-60 small">Universitas Muria Kudus (UMK)</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom copyright bar --}}
        <div style="height:1px; background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);"></div>
        <div class="py-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <p class="mb-0 small" style="opacity: 0.7;">&copy; 2026 {{ $namaKelompok }}.</p>
        </div>
    </div>
</footer>
