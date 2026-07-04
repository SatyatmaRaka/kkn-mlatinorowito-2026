<x-app-layout>
    <x-slot name="header">QR Code Absensi Posko</x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="premium-card border-0 p-4 p-md-5 text-center">
                <span class="badge bg-success mb-3 d-print-none">QR Tetap</span>
                <h2 class="h5 fw-bold mb-2">QR Absensi Posko</h2>
                <p class="text-muted small mb-4 d-print-none">QR ini tidak berubah setiap hari — cetak sekali atau tampilkan di tablet posko sepanjang KKN.</p>

                <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
                <p class="small text-muted mb-1">Jam absensi: <strong>{{ $windowLabel }}</strong></p>
                <p class="small text-break text-muted mb-4 d-print-none">{{ $checkInUrl }}</p>

                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <button type="button" class="btn btn-primary rounded-pill px-4" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak QR
                    </button>
                    <a href="{{ route('panel.absensi.display') }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-tablet me-1"></i> Mode Tablet
                    </a>
                    <form action="{{ route('panel.absensi.qr.regenerate') }}" method="POST" class="d-inline" onsubmit="return confirm('Buat token QR baru? QR yang sudah dicetak/dibagikan tidak akan valid lagi.')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                            <i class="bi bi-arrow-repeat me-1"></i> Buat Ulang QR
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="premium-card border-0 p-4">
                <h2 class="h5 fw-bold mb-3">Panduan Absensi QR</h2>
                <ol class="text-muted small mb-4 ps-3">
                    <li class="mb-2">Cetak QR <strong>sekali</strong> atau pasang tablet dengan <em>Mode Tablet</em> di posko.</li>
                    <li class="mb-2">Setiap anggota scan QR dengan kamera HP.</li>
                    <li class="mb-2">Login dengan akun pribadi (bukan titip teman).</li>
                    <li class="mb-2">Tekan <strong>Konfirmasi Kehadiran</strong> dalam jam absensi.</li>
                    <li>Koordinator pantau rekap di menu <strong>Rekap Absensi</strong>.</li>
                </ol>
                <div class="alert alert-info border-0 small mb-0">
                    <strong>Keamanan:</strong> Absensi tetap hanya bisa dalam jam yang ditentukan dan satu kali per hari per anggota. Gunakan <em>Buat Ulang QR</em> hanya jika QR bocor ke luar posko.
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {
                .admin-sidebar, .admin-mobile-topbar, header, .btn, .col-lg-6:last-child, .d-print-none { display: none !important; }
                .admin-main { margin-left: 0 !important; padding: 0 !important; }
                .premium-card { box-shadow: none !important; border: 2px solid #000 !important; }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            new QRCode(document.getElementById('qrcode'), {
                text: @json($checkInUrl),
                width: 256,
                height: 256,
                correctLevel: QRCode.CorrectLevel.H
            });
        </script>
    @endpush
</x-app-layout>
