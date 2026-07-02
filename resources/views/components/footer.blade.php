@php
    $namaKelompok = $pengaturan['nama_kelompok'] ?? 'KKN UMK Mlatinorowito 2026';
    $tagline = $pengaturan['tagline'] ?? 'Berdampak dalam Membangun Desa Mandiri dan Berkelanjutan';
    $instagram = $pengaturan['instagram'] ?? '@kknumk.mlatinorowito.26';
    $instagramHandle = ltrim($instagram, '@');
    $instagramUrl = str_starts_with($instagram, 'http')
        ? $instagram
        : 'https://instagram.com/' . $instagramHandle;
@endphp

<footer class="text-white py-5" style="background: linear-gradient(to bottom, var(--umk-blue), #020617);">
    <div class="container px-3 px-md-5">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-4 text-center text-md-start">
                <p class="fw-bold fs-5 mb-0" style="letter-spacing: -0.02em;">{{ $namaKelompok }}</p>
            </div>

            <div class="col-12 col-md-4 text-center">
                <p class="mb-0 small opacity-75 fw-light">
                    {{ $tagline }}
                </p>
            </div>

            <div class="col-12 col-md-4 text-center text-md-end">
                <a
                    href="{{ $instagramUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-white text-decoration-none small d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill"
                    style="background: rgba(255,255,255,0.1); transition: all 0.3s ease;"
                    onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'"
                >
                    <i class="bi bi-instagram fs-6"></i>
                    <span class="fw-medium">{{ str_starts_with($instagram, '@') ? $instagram : '@' . $instagramHandle }}</span>
                </a>
            </div>
        </div>

        <div class="border-top border-white border-opacity-10 mt-5 pt-4 text-center">
            <p class="mb-0 small opacity-50">&copy; 2026 {{ $namaKelompok }}.</p>
        </div>
    </div>
</footer>
