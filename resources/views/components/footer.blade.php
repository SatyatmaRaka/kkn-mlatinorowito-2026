@php
    $namaKelompok = $pengaturan['nama_kelompok'] ?? 'KKN UMK Mlatinorowito 2026';
    $tagline = $pengaturan['tagline'] ?? 'Berdampak dalam Membangun Desa Mandiri dan Berkelanjutan';
    $instagram = $pengaturan['instagram'] ?? '@kkn_mlatinorowito2026';
    $instagramHandle = ltrim($instagram, '@');
    $instagramUrl = str_starts_with($instagram, 'http')
        ? $instagram
        : 'https://instagram.com/' . $instagramHandle;
@endphp

<footer class="text-white py-5" style="background-color: var(--umk-blue);">
    <div class="container px-3 px-md-5">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-4 text-center text-md-start">
                <p class="fw-semibold mb-0">{{ $namaKelompok }}</p>
            </div>

            <div class="col-12 col-md-4 text-center">
                <p class="mb-0 small opacity-75">
                    {{ $tagline }}
                </p>
            </div>

            <div class="col-12 col-md-4 text-center text-md-end">
                <a
                    href="{{ $instagramUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-white text-decoration-none small"
                >
                    📷 {{ str_starts_with($instagram, '@') ? $instagram : '@' . $instagramHandle }}
                </a>
            </div>
        </div>

        <div class="border-top border-white border-opacity-25 mt-4 pt-4 text-center">
            <p class="mb-0 small">&copy; 2026 {{ $namaKelompok }}. All rights reserved.</p>
        </div>
    </div>
</footer>
